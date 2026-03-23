<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Campaign;
use App\Models\Order;
use App\Services\NotificationService;
use Carbon\Carbon;

class DeductCampaignBudget extends Command
{
    protected $signature = 'campaigns:deduct-daily {--backfill : Process all missed days since start_date}';
    protected $description = 'Deduct daily budget from seller collections for active campaigns';

    public function handle()
    {
        $today = Carbon::today();

        // 1. Auto-expire campaigns past end date
        Campaign::where('end_date', '<', $today)
            ->where('status', '!=', 'Ended')
            ->update(['status' => 'Ended']);

        // 2. Get all active campaigns that need deduction
        $activeCampaigns = Campaign::where('status', 'Active')
            ->where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->where(function ($q) use ($today) {
                $q->whereNull('last_deducted_at')
                  ->orWhere('last_deducted_at', '<', $today);
            })
            ->with('product')
            ->get();

        $deducted = 0;
        $paused = 0;
        $backfillDays = 0;

        /** @var Campaign $campaign */
        foreach ($activeCampaigns as $campaign) {
            if (!$campaign->product) {
                continue;
            }

            $sellerId = $campaign->product->seller_id;

            // Calculate how many days need deduction
            if ($this->option('backfill')) {
                // Backfill: deduct for every missed day from start_date (or last_deducted_at+1) to today
                $fromDate = $campaign->last_deducted_at
                    ? Carbon::parse($campaign->last_deducted_at)->addDay()
                    : Carbon::parse($campaign->start_date);
                $toDate = $today->copy()->min(Carbon::parse($campaign->end_date));
            } else {
                // Normal: only deduct for today
                $fromDate = $today->copy();
                $toDate = $today->copy();
            }

            $currentDate = $fromDate->copy();
            while ($currentDate->lte($toDate)) {
                $balance = Campaign::getSellerBalance($sellerId);

                if ($balance >= $campaign->daily_budget) {
                    $campaign->total_deducted += $campaign->daily_budget;
                    $campaign->last_deducted_at = $currentDate->toDateString();
                    $campaign->save();
                    $deducted++;
                    if ($this->option('backfill')) {
                        $backfillDays++;
                    }
                } else {
                    // Not enough balance — pause the campaign
                    $campaign->status = 'Paused';
                    $campaign->save();
                    $paused++;

                    // Notify seller: ad paused due to low funds
                    if ($sellerId) {
                        NotificationService::adPausedLowBalance($sellerId, $campaign->campaign_name);
                    }
                    break; // Stop trying more days for this campaign
                }

                $currentDate->addDay();
            }
        }

        if ($this->option('backfill')) {
            $this->info("Backfill complete: {$deducted} day-deductions applied across {$backfillDays} backfill days, {$paused} campaigns paused.");
        } else {
            $this->info("Daily deduction complete: {$deducted} deducted, {$paused} paused (insufficient balance).");
        }
        return 0;
    }
}
