@extends('welcome')

@section('content')
<div class="main-content" style="padding: 20px;">
    
    <div style="margin-bottom: 20px;">
        <h2 style="font-size: 24px; font-weight: 700; color: #111;">Financial Ledger</h2>
        <p style="color: #555;">Track incoming payments and revenue from orders.</p>
    </div>

    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px;">
        <x-stat-card 
            title="Total Revenue Collected"
            value="₹{{ number_format($totalRevenue, 2) }}"
            valueColor="#007600"
        />

        <x-stat-card 
            title="Pending Payments"
            value="₹{{ number_format($pendingAmount, 2) }}"
            valueColor="#c40000"
        />

        <x-stat-card 
            title="Payable Balance"
            value="₹{{ number_format($payableBalance, 2) }}"
            valueColor="#007185"
            subtitle="After ad deductions & completed payouts"
        >
            @if($payableBalance >= 100)
                <form action="{{ route('payments.withdraw') }}" method="POST" style="margin-top: 15px;"
                      onsubmit="return confirm('Request withdrawal of ₹{{ number_format($payableBalance, 2) }}?')">
                    @csrf
                    <button type="submit" style="background: #007185; color: white; border: none; padding: 8px 20px; border-radius: 4px; font-size: 13px; font-weight: 600; cursor: pointer; width: 100%;">
                        <i class="fas fa-money-bill-wave"></i> Withdraw
                    </button>
                </form>
            @endif
        </x-stat-card>

        <x-stat-card 
            title="Total Paid Out"
            value="₹{{ number_format($totalPaidOut, 2) }}"
            valueColor="#007600"
            subtitle="Next payout: <strong>{{ $nextPayoutDate }}</strong>"
            subtitleColor="#007185"
            borderColor="#008577"
        />
    </div>

    @if(session('success'))
        <div style="background-color: #067D62; color: white; padding: 12px; margin-bottom: 20px; border-radius: 4px;">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background-color: #d32f2f; color: white; padding: 12px; margin-bottom: 20px; border-radius: 4px;">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <div style="background:white; border:1px solid #d5d9d9; border-radius:8px; overflow:hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; font-family: sans-serif;">
            <thead style="background:#f0f2f2; border-bottom: 1px solid #eaeded;">
                <tr>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">DATE</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">TRANSACTION ID</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">ORDER REF</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">PAYMENT METHOD</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">SOURCE</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">AMOUNT</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">STATUS</th>
                    <th style="padding:15px; text-align:left; color:#444; font-size: 13px;">ACTION</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $pay)
                <tr style="border-bottom:1px solid #eaeded; transition: background 0.2s;">
                    <td style="padding:15px; color: #555;">
                        {{ $pay->created_at->format('d M Y') }}<br>
                        <small>{{ $pay->created_at->format('h:i A') }}</small>
                    </td>
                    <td style="padding:15px; font-weight:bold; color: #333;">{{ $pay->payment_id }}</td>
                    <td style="padding:15px;">
                        <span style="background: #e7f4f5; color: #007185; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                            {{ $pay->order_id }}
                        </span>
                    </td>
                    <td style="padding:15px;">
                        <span style="background: #f0f2f2; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: 500;">
                            {{ $pay->payment_method }}
                        </span>
                    </td>
                    <td style="padding:15px;">
                        @if($pay->order && $pay->order->order_source == 'online')
                            <span style="background: #e6f7e6; color: #007600; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-shopping-cart"></i> ONLINE
                            </span>
                        @else
                            <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 11px; font-weight: 600;">
                                <i class="fas fa-user-tie"></i> MANUAL
                            </span>
                        @endif
                    </td>
                    <td style="padding:15px; font-weight: bold;">₹{{ number_format($pay->amount, 2) }}</td>
                    <td style="padding:15px;">
                        @if($pay->status == 'Completed')
                            <span style="color:#007600; font-weight:bold; display: flex; align-items: center;">
                                <span style="font-size: 16px; margin-right: 5px;">●</span> Paid
                            </span>
                        @else
                            <span style="color:#c40000; font-weight:bold; display: flex; align-items: center;">
                                <span style="font-size: 16px; margin-right: 5px;">●</span> Pending
                            </span>
                        @endif
                    </td>
                    <td style="padding:15px;">
                        @if($pay->status == 'Pending')
                            <form action="{{ route('payments.update', $pay->id) }}" method="POST">
                                @csrf
                                <button type="submit" style="background:#f0c14b; border:1px solid #a88734; cursor:pointer; padding:6px 12px; border-radius:3px; font-weight: bold; color: #111;">
                                    Mark Received
                                </button>
                            </form>
                        @else
                            <span style="color:#aaa; font-style: italic;">Locked</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="text-align: center; padding: 40px; color: #777;">
                        <h3 style="margin: 0; color: #333;">No Transactions Yet</h3>
                        <p>Go to the <b>Order</b> section and create a new order to see payment data here.</p>
                        <a href="{{ route('orders.create') }}" style="color: #007185; text-decoration: none; font-weight: bold;">Create an Order &rarr;</a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Payout History Section --}}
    <div style="margin-top: 40px; margin-bottom: 20px;">
        <h2 style="font-size: 22px; font-weight: 700; color: #111;">Payout History</h2>
        <p style="color: #555; font-size: 13px;">Payouts are automatically generated every 7 days and sent to admin for approval. Once approved, the amount is transferred to your bank account.</p>
    </div>

    <div style="background: #e7f6f8; border: 1px solid #b8daff; border-radius: 8px; padding: 14px 18px; margin-bottom: 20px; font-size: 13px; color: #0c5460;">
        <strong>ℹ How payouts work:</strong> Every 7 days, a payout request is automatically created from your delivered order earnings (minus ad spend). The admin reviews and approves it, then the amount is transferred to your registered bank account.
    </div>

    <div style="background:white; border:1px solid #d5d9d9; border-radius:8px; overflow:hidden; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <table style="width:100%; border-collapse:collapse; font-family: sans-serif;">
            <thead style="background:#f0f2f2; border-bottom: 1px solid #eaeded;">
                <tr>
                    <th style="padding:14px; text-align:left; color:#444; font-size: 12px; text-transform: uppercase;">Payout ID</th>
                    <th style="padding:14px; text-align:left; color:#444; font-size: 12px; text-transform: uppercase;">Period</th>
                    <th style="padding:14px; text-align:right; color:#444; font-size: 12px; text-transform: uppercase;">Earnings</th>
                    <th style="padding:14px; text-align:right; color:#444; font-size: 12px; text-transform: uppercase;">Ad Deductions</th>
                    <th style="padding:14px; text-align:right; color:#444; font-size: 12px; text-transform: uppercase;">Net Payout</th>
                    <th style="padding:14px; text-align:center; color:#444; font-size: 12px; text-transform: uppercase;">Status</th>
                    <th style="padding:14px; text-align:left; color:#444; font-size: 12px; text-transform: uppercase;">Details</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payouts as $payout)
                <tr style="border-bottom:1px solid #eaeded;">
                    <td style="padding:14px; font-weight:bold; color: #333; font-size: 13px;">{{ $payout->payout_id }}</td>
                    <td style="padding:14px; font-size: 13px; color: #555;">
                        {{ $payout->period_start->format('M d') }} — {{ $payout->period_end->format('M d, Y') }}
                    </td>
                    <td style="padding:14px; text-align:right; font-size: 13px;">₹{{ number_format($payout->amount, 2) }}</td>
                    <td style="padding:14px; text-align:right; font-size: 13px; color: #c40000;">-₹{{ number_format($payout->ad_deductions, 2) }}</td>
                    <td style="padding:14px; text-align:right; font-size: 14px; font-weight: bold; color: #007600;">₹{{ number_format($payout->net_amount, 2) }}</td>
                    <td style="padding:14px; text-align:center;">
                        @php
                            $statusColors = [
                                'pending' => ['bg' => '#fff3cd', 'color' => '#856404', 'label' => 'Pending Review'],
                                'approved' => ['bg' => '#d1ecf1', 'color' => '#0c5460', 'label' => 'Approved'],
                                'completed' => ['bg' => '#d4edda', 'color' => '#155724', 'label' => 'Paid'],
                                'rejected' => ['bg' => '#f8d7da', 'color' => '#721c24', 'label' => 'Rejected'],
                            ];
                            $sc = $statusColors[$payout->status] ?? ['bg' => '#f0f0f0', 'color' => '#333', 'label' => $payout->status];
                        @endphp
                        <span style="background: {{ $sc['bg'] }}; color: {{ $sc['color'] }}; padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600;">
                            {{ $sc['label'] }}
                        </span>
                    </td>
                    <td style="padding:14px; font-size: 12px; color: #555;">
                        @if($payout->status === 'completed')
                            <div>Txn: <strong>{{ $payout->transaction_reference }}</strong></div>
                            <div style="color: #999;">{{ $payout->completed_at ? $payout->completed_at->format('M d, Y') : '' }}</div>
                        @elseif($payout->status === 'rejected')
                            <div style="color: #c40000;">{{ $payout->rejection_reason }}</div>
                        @elseif($payout->status === 'approved')
                            <div style="color: #007185;">Processing — will be in your bank soon</div>
                        @else
                            <div style="color: #856404;">Under admin review</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #777;">
                        <h3 style="margin: 0; color: #333;">No Payouts Yet</h3>
                        <p>Payouts are auto-generated every 7 days when you have delivered orders.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection