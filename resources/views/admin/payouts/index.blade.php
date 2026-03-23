@extends('admin.layout')
@section('title', 'Payouts')
@section('header', 'Payout Management')

@section('content')
<div style="margin-bottom: 24px;">
    <p style="color: var(--gray-500); margin-bottom: 16px;">Review and process seller payout requests. Payouts are auto-generated every 7 days.</p>
</div>

{{-- Summary Cards --}}
<div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px; margin-bottom: 24px;">
    <div style="background: #fff7ed; border: 1px solid #fed7aa; border-radius: 8px; padding: 20px;">
        <div style="font-size: 13px; color: #9a3412; font-weight: 600; text-transform: uppercase;">Pending Payouts</div>
        <div style="font-size: 28px; font-weight: 700; color: #c2410c; margin-top: 4px;">{{ $pendingCount }}</div>
        <div style="font-size: 14px; color: #ea580c; margin-top: 2px;">₹{{ number_format($pendingAmount, 2) }} total</div>
    </div>
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 20px;">
        <div style="font-size: 13px; color: #166534; font-weight: 600; text-transform: uppercase;">Completed Payouts</div>
        <div style="font-size: 28px; font-weight: 700; color: #15803d; margin-top: 4px;">{{ $completedCount }}</div>
        <div style="font-size: 14px; color: #16a34a; margin-top: 2px;">₹{{ number_format($completedAmount, 2) }} disbursed</div>
    </div>
    <div style="background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 8px; padding: 20px;">
        <div style="font-size: 13px; color: #1e40af; font-weight: 600; text-transform: uppercase;">Payout Cycle</div>
        <div style="font-size: 28px; font-weight: 700; color: #1d4ed8; margin-top: 4px;">7 Days</div>
        <div style="font-size: 14px; color: #2563eb; margin-top: 2px;">Auto-generated weekly</div>
    </div>
</div>

{{-- Filter Tabs --}}
<div style="display: flex; gap: 8px; margin-bottom: 16px;">
    @php
        $filters = ['all' => 'All', 'pending' => 'Pending', 'approved' => 'Approved', 'completed' => 'Completed', 'rejected' => 'Rejected'];
    @endphp
    @foreach($filters as $key => $label)
        <a href="{{ route('admin.payouts') }}?status={{ $key }}"
           style="padding: 8px 16px; border-radius: 6px; text-decoration: none; font-size: 13px; font-weight: 500;
                  {{ $status === $key ? 'background: var(--gray-900); color: white;' : 'background: var(--gray-100); color: var(--gray-600);' }}">
            {{ $label }}
        </a>
    @endforeach
</div>

@if(session('success'))
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #166534; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
        ✓ {{ session('success') }}
    </div>
@endif
@if(session('error'))
    <div style="background: #fef2f2; border: 1px solid #fecaca; color: #991b1b; padding: 12px 16px; border-radius: 8px; margin-bottom: 16px; font-size: 14px;">
        ✗ {{ session('error') }}
    </div>
@endif

{{-- Payouts Table --}}
<div style="background: white; border: 1px solid var(--gray-200); border-radius: 8px; overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: var(--gray-50); border-bottom: 1px solid var(--gray-200);">
                <th style="padding: 12px 16px; text-align: left; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Payout ID</th>
                <th style="padding: 12px 16px; text-align: left; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Seller</th>
                <th style="padding: 12px 16px; text-align: left; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Period</th>
                <th style="padding: 12px 16px; text-align: right; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Gross</th>
                <th style="padding: 12px 16px; text-align: right; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Ad Deduct</th>
                <th style="padding: 12px 16px; text-align: right; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Net Payout</th>
                <th style="padding: 12px 16px; text-align: center; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Status</th>
                <th style="padding: 12px 16px; text-align: center; font-size: 12px; color: var(--gray-500); font-weight: 600; text-transform: uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($payouts as $payout)
            <tr style="border-bottom: 1px solid var(--gray-100);">
                <td style="padding: 14px 16px; font-weight: 600; font-size: 13px;">
                    <a href="{{ route('admin.payouts.show', $payout->id) }}" style="color: #1d4ed8; text-decoration: none;">
                        {{ $payout->payout_id }}
                    </a>
                </td>
                <td style="padding: 14px 16px; font-size: 13px;">
                    <div style="font-weight: 500;">{{ $payout->seller->name ?? 'Unknown' }}</div>
                    <div style="font-size: 11px; color: var(--gray-400);">{{ $payout->seller->business_name ?? '' }}</div>
                </td>
                <td style="padding: 14px 16px; font-size: 12px; color: var(--gray-500);">
                    {{ $payout->period_start->format('M d') }} — {{ $payout->period_end->format('M d, Y') }}
                </td>
                <td style="padding: 14px 16px; text-align: right; font-size: 13px;">₹{{ number_format($payout->amount, 2) }}</td>
                <td style="padding: 14px 16px; text-align: right; font-size: 13px; color: #dc2626;">-₹{{ number_format($payout->ad_deductions, 2) }}</td>
                <td style="padding: 14px 16px; text-align: right; font-size: 14px; font-weight: 700; color: #047857;">₹{{ number_format($payout->net_amount, 2) }}</td>
                <td style="padding: 14px 16px; text-align: center;">
                    @php
                        $colors = [
                            'pending' => 'background:#fef3c7;color:#92400e;',
                            'approved' => 'background:#dbeafe;color:#1e40af;',
                            'completed' => 'background:#dcfce7;color:#166534;',
                            'rejected' => 'background:#fee2e2;color:#991b1b;',
                        ];
                    @endphp
                    <span style="padding: 4px 10px; border-radius: 12px; font-size: 11px; font-weight: 600; {{ $colors[$payout->status] ?? '' }}">
                        {{ ucfirst($payout->status) }}
                    </span>
                </td>
                <td style="padding: 14px 16px; text-align: center;">
                    <a href="{{ route('admin.payouts.show', $payout->id) }}" 
                       style="background: var(--gray-900); color: white; padding: 6px 14px; border-radius: 6px; font-size: 12px; text-decoration: none; font-weight: 500;">
                        Review
                    </a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="8" style="padding: 40px; text-align: center; color: var(--gray-400);">
                    No payouts found for this filter.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
