<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\SellerReputationService;
use Illuminate\Console\Command;

class RecalculateSellerReputation extends Command
{
    protected $signature = 'sellers:recalculate-reputation {--seller= : Specific seller ID}';
    protected $description = 'Recalculate and cache seller reputation score and badge';

    public function handle(SellerReputationService $reputationService): int
    {
        $sellerId = $this->option('seller');

        if ($sellerId) {
            $seller = User::query()
                ->where('id', (int) $sellerId)
                ->where('status', 'active')
                ->first();

            if (!$seller) {
                $this->error('Active seller not found for the given ID.');
                return self::FAILURE;
            }

            $data = $reputationService->persistForSeller($seller);
            $this->info("Seller #{$seller->id} reputation updated: {$data['score']} ({$data['badge']})");
            return self::SUCCESS;
        }

        $total = 0;
        User::query()
            ->where('status', 'active')
            ->whereHas('products')
            ->chunkById(100, function ($sellers) use ($reputationService, &$total) {
                foreach ($sellers as $seller) {
                    $data = $reputationService->persistForSeller($seller);
                    $this->line("Seller #{$seller->id} => {$data['score']} ({$data['badge']})");
                    $total++;
                }
            });

        $this->info("Reputation refresh complete. Updated sellers: {$total}");

        return self::SUCCESS;
    }
}
