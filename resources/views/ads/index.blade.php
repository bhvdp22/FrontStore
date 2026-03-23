@extends('welcome')

@section('content')
<div class="main-content">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <div>
            <h2 style="font-size: 24px; font-weight: 700; color: #111; margin-bottom: 5px;">Campaign Manager</h2>
            <p style="color: #555; margin: 0;">Monitor and control your ad campaigns.</p>
        </div>
        <a href="{{ route('ads.create') }}" class="btn-dark" style="background-color: #f0c14b; color: #111; border: 1px solid #a88734; font-weight: bold; padding: 8px 15px; text-decoration: none; border-radius: 3px;">
            Create Campaign
        </a>
    </div>

    {{-- Balance & Spend Overview --}}
    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 15px; margin-bottom: 25px;">
        <x-stat-card 
            title="Total Earnings"
            value="₹{{ number_format($totalEarnings, 2) }}"
            subtitle="From delivered orders"
            subtitleColor="#007600"
        />
        <x-stat-card 
            title="Total Ad Spend"
            value="₹{{ number_format($totalAdSpend, 2) }}"
            valueColor="#c7511f"
            subtitle="Deducted from collections"
        />
        <x-stat-card 
            title="Available Balance"
            value="₹{{ number_format($balance, 2) }}"
            valueColor="{{ $balance > 0 ? '#067d62' : '#c40000' }}"
            subtitle="{{ $balance > 0 ? 'Available for ads' : 'No balance available' }}"
            subtitleColor="{{ $balance > 0 ? '#067d62' : '#c40000' }}"
            background="{{ $balance > 0 ? '#f0faf0' : '#fff4f4' }}"
            borderColor="{{ $balance > 0 ? '#067d62' : '#c40000' }}"
        />
    </div>

    {{-- Low/Zero balance warning --}}
    @if($balance <= 0)
    <div style="background: #fff4f4; border: 1px solid #c40000; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-exclamation-triangle" style="color: #c40000; font-size: 22px;"></i>
        <div>
            <div style="font-weight: 700; color: #c40000; margin-bottom: 3px;">Insufficient Balance — Add Funds</div>
            <div style="color: #555; font-size: 13px;">Your collected earnings are fully utilized. Get more orders delivered to increase your balance and run ad campaigns. Active campaigns will be auto-paused until balance is available.</div>
        </div>
    </div>
    @elseif($balance < 200)
    <div style="background: #fff9e6; border: 1px solid #e77600; border-radius: 8px; padding: 18px 20px; margin-bottom: 20px; display: flex; align-items: center; gap: 12px;">
        <i class="fas fa-info-circle" style="color: #e77600; font-size: 22px;"></i>
        <div>
            <div style="font-weight: 700; color: #e77600; margin-bottom: 3px;">Low Balance Warning</div>
            <div style="color: #555; font-size: 13px;">Your available balance is only ₹{{ number_format($balance, 2) }}. Campaigns may auto-pause if balance runs out. Deliver more orders to top up.</div>
        </div>
    </div>
    @endif

    @if(session('success'))
        <div style="background-color: #067D62; color: white; padding: 12px; margin-bottom: 20px; border-radius: 4px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #c40000; color: white; padding: 12px; margin-bottom: 20px; border-radius: 4px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div style="background: white; border: 1px solid #d5d9d9; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
            <thead style="background-color: #f0f2f2; border-bottom: 1px solid #eaeded;">
                <tr>
                    <th style="padding: 15px; text-align: left; color: #444;">CAMPAIGN NAME</th>
                    <th style="padding: 15px; text-align: left; color: #444;">PRODUCT (SKU)</th>
                    <th style="padding: 15px; text-align: left; color: #444;">DAILY BUDGET</th>
                    <th style="padding: 15px; text-align: left; color: #444;">TOTAL SPENT</th>
                    <th style="padding: 15px; text-align: left; color: #444;">STATUS</th>
                    <th style="padding: 15px; text-align: left; color: #444;">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                @forelse($campaigns as $ad)
                <tr style="border-bottom: 1px solid #eaeded;">
                    
                    <td style="padding: 15px; font-weight: bold; color: #007185;">
                        {{ $ad->campaign_name }}
                        <div style="font-size: 11px; color: #888; font-weight: normal; margin-top: 3px;">
                            {{ \Carbon\Carbon::parse($ad->start_date)->format('M d') }} — {{ \Carbon\Carbon::parse($ad->end_date)->format('M d, Y') }}
                        </div>
                    </td>

                    <td style="padding: 15px;">
                        <span style="background: #e7f4f5; color: #333; padding: 4px 8px; border-radius: 4px; font-size: 12px;">
                            {{ $ad->sku }}
                        </span>
                    </td>

                    <td style="padding: 15px; font-weight: bold;">
                        ₹{{ number_format($ad->daily_budget, 2) }}<span style="color: #888; font-weight: normal; font-size: 11px;">/day</span>
                    </td>

                    <td style="padding: 15px; font-weight: bold; color: #c7511f;">
                        ₹{{ number_format($ad->total_deducted, 2) }}
                    </td>

                    <td style="padding: 15px;">
                        @if($ad->status == 'Active')
                            <span style="color: #007600; font-weight: bold; display: flex; align-items: center;">
                                <span style="font-size:18px; line-height:0; margin-right:5px;">●</span> Active
                            </span>
                        @elseif($ad->status == 'Ended')
                            <span style="color: #888; font-weight: bold; display: flex; align-items: center;">
                                <span style="font-size:18px; line-height:0; margin-right:5px;">●</span> Ended
                            </span>
                        @else
                            <span style="color: #e77600; font-weight: bold; display: flex; align-items: center;">
                                <span style="font-size:18px; line-height:0; margin-right:5px;">●</span> Paused
                            </span>
                        @endif
                    </td>

                    <td style="padding: 15px;">
                        @if($ad->status !== 'Ended')
                        <form action="{{ route('ads.toggle', $ad->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @if($ad->status == 'Active')
                                <button type="submit" style="background: #fff; border: 1px solid #888; color: #333; cursor: pointer; padding: 5px 10px; border-radius: 3px; font-size: 12px; margin-right: 5px;">
                                    Pause
                                </button>
                            @else
                                <button type="submit" style="background: #e7f4f5; border: 1px solid #007185; color: #007185; cursor: pointer; padding: 5px 10px; border-radius: 3px; font-size: 12px; margin-right: 5px;" {{ $balance < $ad->daily_budget ? 'disabled title=Insufficient balance' : '' }}>
                                    Resume
                                </button>
                            @endif
                        </form>
                        @endif

                        <form action="{{ route('ads.destroy', $ad->id) }}" method="POST" onsubmit="return confirm('Delete this campaign completely?');" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color: #c40000; background: none; border: none; cursor: pointer; text-decoration: underline; font-size: 12px;">
                                Delete
                            </button>
                        </form>
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 40px; color: #777;">
                        No campaigns found. Create your first campaign to start advertising!
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div style="margin-top: 15px; padding: 12px 16px; background: #f7f9fa; border-radius: 6px; font-size: 12px; color: #666;">
        <i class="fas fa-info-circle" style="color: #007185;"></i>
        <strong>How it works:</strong> Daily budget is deducted from your delivered order earnings each day at midnight. If your balance runs out, campaigns are automatically paused. Deliver more orders to increase your available balance.
    </div>
</div>
@endsection