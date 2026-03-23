@extends('admin.layout')
@section('title', 'Payout Details')
@section('header', 'Payout: ' . $payout->payout_id)

@section('content')

@if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px;">
        ✗ {{ session('error') }}
    </div>
@endif

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px;">
    {{-- Left Column --}}
    <div>
        {{-- Payout Summary --}}
        <div style="background: white; border: 1px solid var(--gray-200); border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--gray-800);">Payout Summary</h3>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px;">
                <div>
                    <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600;">Gross Earnings</div>
                    <div style="font-size: 22px; font-weight: 700; color: var(--gray-800);">₹{{ number_format($payout->amount, 2) }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600;">Ad Deductions</div>
                    <div style="font-size: 22px; font-weight: 700; color: #dc2626;">-₹{{ number_format($payout->ad_deductions, 2) }}</div>
                </div>
                <div>
                    <div style="font-size: 12px; color: var(--gray-500); text-transform: uppercase; font-weight: 600;">Net Payout</div>
                    <div style="font-size: 22px; font-weight: 700; color: #047857;">₹{{ number_format($payout->net_amount, 2) }}</div>
                </div>
            </div>
            <div style="margin-top: 16px; padding-top: 16px; border-top: 1px solid var(--gray-100);">
                <div style="display: flex; gap: 24px; font-size: 13px; color: var(--gray-500);">
                    <span><strong>Period:</strong> {{ $payout->period_start->format('M d, Y') }} — {{ $payout->period_end->format('M d, Y') }}</span>
                    <span><strong>Created:</strong> {{ $payout->created_at->format('M d, Y h:i A') }}</span>
                    @php
                        $colors = [
                            'pending' => 'background:#fef3c7;color:#92400e;',
                            'approved' => 'background:#dbeafe;color:#1e40af;',
                            'completed' => 'background:#dcfce7;color:#166534;',
                            'rejected' => 'background:#fee2e2;color:#991b1b;',
                        ];
                    @endphp
                    <span style="padding: 2px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; {{ $colors[$payout->status] ?? '' }}">
                        {{ ucfirst($payout->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Orders in Period --}}
        <div style="background: white; border: 1px solid var(--gray-200); border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--gray-800);">Delivered Orders in Period ({{ $orders->count() }})</h3>
            @if($orders->count() > 0)
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--gray-200);">
                        <th style="padding: 8px 0; text-align: left; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Order ID</th>
                        <th style="padding: 8px 0; text-align: left; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Product</th>
                        <th style="padding: 8px 0; text-align: right; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Total</th>
                        <th style="padding: 8px 0; text-align: right; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Seller Earnings</th>
                        <th style="padding: 8px 0; text-align: left; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Delivered</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    <tr style="border-bottom: 1px solid var(--gray-50);">
                        <td style="padding: 8px 0; font-weight: 500;">{{ $order->order_id }}</td>
                        <td style="padding: 8px 0;">{{ $order->product_name }}</td>
                        <td style="padding: 8px 0; text-align: right;">₹{{ number_format($order->total_price, 2) }}</td>
                        <td style="padding: 8px 0; text-align: right; color: #047857; font-weight: 600;">₹{{ number_format($order->seller_earnings, 2) }}</td>
                        <td style="padding: 8px 0; font-size: 12px; color: var(--gray-400);">{{ $order->updated_at->format('M d') }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="border-top: 2px solid var(--gray-200);">
                        <td colspan="3" style="padding: 10px 0; font-weight: 700;">Total</td>
                        <td style="padding: 10px 0; text-align: right; font-weight: 700; color: #047857;">₹{{ number_format($orders->sum('seller_earnings'), 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
            @else
            <p style="color: var(--gray-400); font-size: 13px;">No delivered orders found in this period.</p>
            @endif
        </div>

        {{-- Campaign Deductions --}}
        <div style="background: white; border: 1px solid var(--gray-200); border-radius: 8px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--gray-800);">Ad Campaign Deductions</h3>
            @if($campaigns->count() > 0)
            <table style="width: 100%; border-collapse: collapse; font-size: 13px;">
                <thead>
                    <tr style="border-bottom: 1px solid var(--gray-200);">
                        <th style="padding: 8px 0; text-align: left; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Campaign</th>
                        <th style="padding: 8px 0; text-align: left; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">SKU</th>
                        <th style="padding: 8px 0; text-align: right; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Daily Budget</th>
                        <th style="padding: 8px 0; text-align: right; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Total Deducted</th>
                        <th style="padding: 8px 0; text-align: center; color: var(--gray-500); font-size: 11px; text-transform: uppercase;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($campaigns as $campaign)
                    <tr style="border-bottom: 1px solid var(--gray-50);">
                        <td style="padding: 8px 0; font-weight: 500;">{{ $campaign->campaign_name }}</td>
                        <td style="padding: 8px 0; font-size: 12px;">{{ $campaign->sku }}</td>
                        <td style="padding: 8px 0; text-align: right;">₹{{ number_format($campaign->daily_budget, 2) }}/day</td>
                        <td style="padding: 8px 0; text-align: right; color: #dc2626; font-weight: 600;">₹{{ number_format($campaign->total_deducted, 2) }}</td>
                        <td style="padding: 8px 0; text-align: center; font-size: 11px; font-weight: 500;">{{ $campaign->status }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @else
            <p style="color: var(--gray-400); font-size: 13px;">No ad campaigns for this seller.</p>
            @endif
        </div>
    </div>

    {{-- Right Column — Seller Info & Actions --}}
    <div>
        {{-- Seller Details --}}
        <div style="background: white; border: 1px solid var(--gray-200); border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--gray-800);">Seller Details</h3>
            <div style="font-size: 13px; line-height: 2;">
                <div><strong>Name:</strong> {{ $payout->seller->name ?? 'N/A' }}</div>
                <div><strong>Business:</strong> {{ $payout->seller->business_name ?? 'N/A' }}</div>
                <div><strong>Email:</strong> {{ $payout->seller->email ?? 'N/A' }}</div>
                <div><strong>Phone:</strong> {{ $payout->seller->phone ?? 'N/A' }}</div>
            </div>
        </div>

        {{-- Bank Details (for payout transfer) --}}
        <div style="background: #f8fafc; border: 2px solid #232f3e; border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: #232f3e;">
                <span style="margin-right: 6px;">🏦</span> Bank Details
            </h3>

            @if($payout->bank_name && $payout->bank_account)
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; margin-bottom: 12px;">
                <div>
                    <div style="font-size: 11px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">Bank Name</div>
                    <div style="background: #f1f5f9; padding: 10px 14px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #1e293b;">
                        {{ $payout->bank_name }}
                    </div>
                </div>
                <div>
                    <div style="font-size: 11px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">Account Number</div>
                    <div style="background: #f1f5f9; padding: 10px 14px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #1e293b; font-family: monospace; letter-spacing: 1px;">
                        {{ $payout->bank_account }}
                    </div>
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                <div>
                    <div style="font-size: 11px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">IFSC Code</div>
                    <div style="background: #f1f5f9; padding: 10px 14px; border-radius: 6px; border: 1px solid #e2e8f0; font-size: 14px; font-weight: 600; color: #1e293b; font-family: monospace;">
                        {{ $payout->ifsc_code ?? 'N/A' }}
                    </div>
                </div>
                <div>
                    <div style="font-size: 11px; color: var(--gray-500); text-transform: uppercase; font-weight: 600; margin-bottom: 4px;">Transfer Amount</div>
                    <div style="background: #ecfdf5; padding: 10px 14px; border-radius: 6px; border: 1px solid #a7f3d0; font-size: 18px; font-weight: 700; color: #047857;">
                        ₹{{ number_format($payout->net_amount, 2) }}
                    </div>
                </div>
            </div>

            @if($payout->status === 'approved')
            <div style="margin-top: 14px; background: #dbeafe; color: #1e40af; padding: 10px 14px; border-radius: 6px; font-size: 12px; font-weight: 500;">
                💡 Transfer <strong>₹{{ number_format($payout->net_amount, 2) }}</strong> to the above bank account and enter the transaction reference below.
            </div>
            @endif

            @else
            <div style="background: #fef3c7; color: #92400e; padding: 12px 14px; border-radius: 6px; font-size: 13px;">
                ⚠ Bank details incomplete. Seller must update their profile before payout can be processed.
            </div>
            @endif
        </div>

        {{-- Transaction Info (if completed) --}}
        @if($payout->transaction_reference)
        <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 12px; color: #166534;">✓ Transaction Complete</h3>
            <div style="font-size: 13px; line-height: 2;">
                <div><strong>Txn Reference:</strong> <span style="font-family: monospace; font-weight: 600;">{{ $payout->transaction_reference }}</span></div>
                <div><strong>Amount Sent:</strong> <span style="font-weight: 700; color: #047857;">₹{{ number_format($payout->net_amount, 2) }}</span></div>
                <div><strong>Sent To:</strong> {{ $payout->bank_name }} — A/c {{ $payout->bank_account }} (IFSC: {{ $payout->ifsc_code }})</div>
                <div><strong>Completed At:</strong> {{ $payout->completed_at ? $payout->completed_at->format('M d, Y h:i A') : 'N/A' }}</div>
            </div>
        </div>
        @endif

        {{-- Rejection Info --}}
        @if($payout->status === 'rejected')
        <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 12px; color: #991b1b;">Rejection Details</h3>
            <div style="font-size: 13px;">
                <div><strong>Reason:</strong> {{ $payout->rejection_reason }}</div>
                <div><strong>Rejected At:</strong> {{ $payout->rejected_at ? $payout->rejected_at->format('M d, Y h:i A') : 'N/A' }}</div>
            </div>
        </div>
        @endif

        {{-- Action Buttons --}}
        @if($payout->status === 'pending')
        <div style="background: white; border: 1px solid var(--gray-200); border-radius: 8px; padding: 24px; margin-bottom: 20px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 16px; color: var(--gray-800);">Actions</h3>
            
            {{-- Approve --}}
            <form action="{{ route('admin.payouts.approve', $payout->id) }}" method="POST" style="margin-bottom: 16px;">
                @csrf
                <textarea name="admin_notes" placeholder="Admin notes (optional)" rows="2"
                    style="width: 100%; padding: 8px 12px; border: 1px solid var(--gray-200); border-radius: 6px; font-size: 13px; margin-bottom: 8px; resize: vertical;"></textarea>
                <button type="submit" style="width: 100%; padding: 10px; background: #047857; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px;">
                    ✓ Approve Payout (₹{{ number_format($payout->net_amount, 2) }})
                </button>
            </form>

            {{-- Reject --}}
            <form action="{{ route('admin.payouts.reject', $payout->id) }}" method="POST">
                @csrf
                <textarea name="rejection_reason" placeholder="Rejection reason (required)" rows="2" required
                    style="width: 100%; padding: 8px 12px; border: 1px solid var(--gray-200); border-radius: 6px; font-size: 13px; margin-bottom: 8px; resize: vertical;"></textarea>
                <button type="submit" style="width: 100%; padding: 10px; background: #dc2626; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px;"
                    onclick="return confirm('Are you sure you want to reject this payout?')">
                    ✗ Reject Payout
                </button>
            </form>
        </div>
        @endif

        @if($payout->status === 'approved')
        <div style="background: white; border: 2px solid #1d4ed8; border-radius: 8px; padding: 24px;">
            <h3 style="font-size: 16px; font-weight: 600; margin-bottom: 6px; color: #1d4ed8;">Complete Payout</h3>

            {{-- Inline bank reminder --}}
            @if($payout->bank_name && $payout->bank_account)
            <div style="background: #eff6ff; border-radius: 6px; padding: 12px 14px; margin-bottom: 16px; font-size: 12px; color: #1e40af; line-height: 1.7;">
                <strong>Send To:</strong><br>
                🏦 {{ $payout->bank_name }}<br>
                💳 A/c: <span style="font-family: monospace; font-weight: 600; letter-spacing: 1px;">{{ $payout->bank_account }}</span><br>
                📋 IFSC: <span style="font-family: monospace; font-weight: 600;">{{ $payout->ifsc_code }}</span><br>
                💰 Amount: <strong style="color: #047857;">₹{{ number_format($payout->net_amount, 2) }}</strong>
            </div>
            @endif

            <form action="{{ route('admin.payouts.complete', $payout->id) }}" method="POST">
                @csrf
                <label style="font-size: 13px; font-weight: 500; display: block; margin-bottom: 4px;">Bank Transaction Reference / UTR <span style="font-weight:400; color: #94a3b8;">(auto-generated if blank)</span></label>
                <input type="text" name="transaction_reference" placeholder="e.g. UTR12345678 or NEFT ref — leave blank to auto-generate"
                    style="width: 100%; padding: 10px 12px; border: 1px solid var(--gray-200); border-radius: 6px; font-size: 14px; margin-bottom: 12px; font-family: monospace;">
                <textarea name="admin_notes" placeholder="Admin notes (optional)" rows="2"
                    style="width: 100%; padding: 8px 12px; border: 1px solid var(--gray-200); border-radius: 6px; font-size: 13px; margin-bottom: 8px; resize: vertical;"></textarea>
                <button type="submit" style="width: 100%; padding: 12px; background: #1d4ed8; color: white; border: none; border-radius: 6px; font-weight: 600; cursor: pointer; font-size: 14px;"
                    onclick="return confirm('Confirm: ₹{{ number_format($payout->net_amount, 2) }} sent to {{ $payout->bank_name }} A/c {{ $payout->bank_account }}?')">
                    ✓ Mark as Completed — ₹{{ number_format($payout->net_amount, 2) }}
                </button>
            </form>
        </div>
        @endif

        <div style="margin-top: 16px;">
            <a href="{{ route('admin.payouts') }}" style="color: var(--gray-500); text-decoration: none; font-size: 13px;">← Back to all payouts</a>
        </div>
    </div>
</div>
@endsection
