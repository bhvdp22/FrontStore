@extends('admin.layout')
@section('title', 'Return Details')
@section('header', 'Return Details')

@section('content')
    <a href="{{ route('admin.returns') }}" class="back-link">Back to returns</a>

    <h1 class="page-title">{{ $return->return_number }}</h1>
    <p class="page-subtitle">Filed {{ $return->created_at->format('d M Y, h:i A') }} — <span class="status-text {{ $return->status }}">{{ $return->status_label }}</span></p>

    <div class="grid-2">
        {{-- Customer --}}
        <div class="card">
            <div class="card-title">Customer</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $return->customer->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Email</div>
                    <div class="dd">{{ $return->customer->email ?? '—' }}</div>
                </div>
            </div>
        </div>

        {{-- Seller --}}
        <div class="card">
            <div class="card-title">Seller</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $return->seller->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Business</div>
                    <div class="dd">{{ $return->seller->business_name ?? '—' }}</div>
                </div>
            </div>
        </div>
    </div>

    {{-- Return Info --}}
    <div class="card">
        <div class="card-title">Return Information</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Product</div>
                <div class="dd">{{ $return->product->name ?? '—' }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Quantity</div>
                <div class="dd">{{ $return->quantity }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Reason</div>
                <div class="dd">{{ $return->reason_label }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Refund Amount</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($return->refund_amount, 2) }}</div>
            </div>
            @if($return->reason_details)
            <div class="detail-item" style="grid-column: span 2;">
                <div class="dt">Details</div>
                <div class="dd">{{ $return->reason_details }}</div>
            </div>
            @endif
            @if($return->seller_notes)
            <div class="detail-item" style="grid-column: span 2;">
                <div class="dt">Seller Notes</div>
                <div class="dd">{{ $return->seller_notes }}</div>
            </div>
            @endif
            @if($return->admin_notes)
            <div class="detail-item" style="grid-column: span 2;">
                <div class="dt">Admin Notes</div>
                <div class="dd">{{ $return->admin_notes }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Timeline --}}
    <div class="card">
        <div class="card-title">Timeline</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Created</div>
                <div class="dd">{{ $return->created_at->format('d M Y, h:i A') }}</div>
            </div>
            @if($return->approved_at)
            <div class="detail-item">
                <div class="dt">Approved</div>
                <div class="dd">{{ $return->approved_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
            @if($return->rejected_at)
            <div class="detail-item">
                <div class="dt">Rejected</div>
                <div class="dd">{{ $return->rejected_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
            @if($return->pickup_scheduled_at)
            <div class="detail-item">
                <div class="dt">Pickup Scheduled</div>
                <div class="dd">{{ $return->pickup_scheduled_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
            @if($return->received_at)
            <div class="detail-item">
                <div class="dt">Received</div>
                <div class="dd">{{ $return->received_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
            @if($return->refund_initiated_at)
            <div class="detail-item">
                <div class="dt">Refund Initiated</div>
                <div class="dd">{{ $return->refund_initiated_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
            @if($return->refund_completed_at)
            <div class="detail-item">
                <div class="dt">Refund Completed</div>
                <div class="dd">{{ $return->refund_completed_at->format('d M Y, h:i A') }}</div>
            </div>
            @endif
        </div>
    </div>

    {{-- Refund Info (if exists) --}}
    @if($return->refund)
    <div class="card">
        <div class="card-title">Refund Record</div>
        <div class="detail-grid">
            <div class="detail-item">
                <div class="dt">Refund #</div>
                <div class="dd">{{ $return->refund->refund_number }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Amount</div>
                <div class="dd"><span class="rupee">₹</span>{{ number_format($return->refund->amount, 2) }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Method</div>
                <div class="dd">{{ $return->refund->refund_method_label }}</div>
            </div>
            <div class="detail-item">
                <div class="dt">Status</div>
                <div class="dd"><span class="status-text {{ $return->refund->status }}">{{ $return->refund->status_label }}</span></div>
            </div>
            @if($return->refund->razorpay_refund_id)
            <div class="detail-item" style="grid-column: span 2;">
                <div class="dt">Razorpay Reference ID</div>
                <div class="dd" style="font-family: monospace; font-size: 14px; letter-spacing: 0.5px; color: #059669; font-weight: 600;">{{ $return->refund->razorpay_refund_id }}</div>
            </div>
            @endif
            @if($return->refund->transaction_id)
            <div class="detail-item">
                <div class="dt">Transaction ID</div>
                <div class="dd" style="font-family: monospace;">{{ $return->refund->transaction_id }}</div>
            </div>
            @endif
            @if($return->refund->failure_reason)
            <div class="detail-item" style="grid-column: span 2;">
                <div class="dt">Failure Reason</div>
                <div class="dd" style="color: #ef4444;">{{ $return->refund->failure_reason }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    {{-- ── Admin Actions ────────────────────────────── --}}
    <div class="card">
        <div class="card-title">Admin Actions</div>

        @if($return->status === 'pending')
            <div class="grid-2">
                {{-- Approve --}}
                <form method="POST" action="{{ route('admin.returns.approve', $return->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="approve_notes">Admin Notes (optional)</label>
                        <textarea name="admin_notes" id="approve_notes" class="form-control" rows="2" placeholder="Reason for approval..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success">Approve Return</button>
                </form>

                {{-- Reject --}}
                <form method="POST" action="{{ route('admin.returns.reject', $return->id) }}">
                    @csrf
                    <div class="form-group">
                        <label for="reject_notes">Rejection Reason (required)</label>
                        <textarea name="admin_notes" id="reject_notes" class="form-control" rows="2" placeholder="Reason for rejection..." required></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Reject Return</button>
                </form>
            </div>
        @endif

        @if(in_array($return->status, ['approved', 'received', 'inspected']) && !$return->refund)
            <form method="POST" action="{{ route('admin.returns.initiate-refund', $return->id) }}">
                @csrf
                <div class="form-row" style="margin-bottom: 16px;">
                    <div class="form-group">
                        <label for="refund_method">Refund Method</label>
                        <select name="refund_method" id="refund_method" class="form-control" required>
                            <option value="original_payment">Original Payment Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI</option>
                            <option value="store_credit">Store Credit</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="amount">Refund Amount</label>
                        <input type="number" step="0.01" name="amount" id="amount" class="form-control" value="{{ $return->refund_amount }}" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Initiate Refund</button>
            </form>
        @endif

        @if($return->refund && $return->refund->status === 'pending')
            <form method="POST" action="{{ route('admin.returns.process-refund', $return->id) }}">
                @csrf
                <div style="padding: 14px; border-radius: 8px; background: #fef3c7; border: 1px solid #f59e0b; margin-bottom: 14px;">
                    <p style="font-size: 14px; color: #92400e; margin: 0; font-weight: 600;">Refund Request Pending</p>
                    <p style="font-size: 13px; color: #92400e; margin: 4px 0 0;">
                        Amount: <span class="rupee">₹</span>{{ number_format($return->refund->amount, 2) }} via {{ $return->refund->refund_method_label }}
                    </p>
                    @if($return->refund->refund_method === 'original_payment')
                    <p style="font-size: 12px; color: #92400e; margin: 4px 0 0;">This will call Razorpay API to process the refund to the customer's original payment method.</p>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Process Refund via Razorpay</button>
            </form>
        @endif

        @if($return->status === 'refund_completed')
            <div style="padding: 14px; border-radius: 8px; background: #d1fae5; border: 1px solid #059669;">
                <p style="font-size: 14px; color: #065f46; margin: 0; font-weight: 600;">Refund Completed Successfully</p>
                @if($return->refund && $return->refund->razorpay_refund_id)
                <p style="font-size: 13px; color: #065f46; margin: 6px 0 0;">Razorpay Reference ID: <strong style="font-family: monospace; letter-spacing: 0.5px;">{{ $return->refund->razorpay_refund_id }}</strong></p>
                @endif
            </div>
        @endif

        @if($return->status === 'rejected')
            <p style="font-size: 13px; color: var(--danger);">This return was rejected.</p>
        @endif
    </div>
@endsection
