@extends('layouts.seller')

@section('title', 'Return ' . $return->return_number . ' - Seller Central')

@section('extra_styles')
<style>
    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
        flex-wrap: wrap;
        gap: 12px;
    }
    .back-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        color: #007185;
        text-decoration: none;
        font-size: 14px;
        margin-bottom: 8px;
    }
    .back-link:hover { text-decoration: underline; }
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #0f1111;
    }
    
    .status-badge {
        display: inline-block;
        padding: 8px 16px;
        border-radius: 25px;
        font-size: 13px;
        font-weight: 600;
    }
    
    .return-grid {
        display: grid;
        grid-template-columns: 1fr 380px;
        gap: 24px;
    }
    
    .card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        overflow: hidden;
        margin-bottom: 20px;
    }
    .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 16px 20px;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff 100%);
        border-bottom: 1px solid #e5e7eb;
    }
    .card-header h3 {
        font-size: 16px;
        font-weight: 600;
        color: #0f1111;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .card-header h3 i { color: #febd69; }
    .card-body { padding: 20px; }
    
    /* Product Summary */
    .product-summary {
        display: flex;
        gap: 16px;
        padding-bottom: 20px;
        border-bottom: 1px solid #e5e7eb;
        margin-bottom: 20px;
    }
    .product-summary img {
        width: 90px;
        height: 90px;
        border-radius: 10px;
        object-fit: cover;
        border: 1px solid #e5e7eb;
    }
    .product-summary h4 {
        font-size: 16px;
        font-weight: 600;
        color: #0f1111;
        margin-bottom: 6px;
    }
    .product-summary p {
        font-size: 13px;
        color: #565959;
        margin-bottom: 4px;
    }
    .product-summary .price {
        font-size: 20px;
        font-weight: 700;
        color: #059669;
    }
    
    /* Info Grid */
    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }
    .info-item label {
        display: block;
        font-size: 11px;
        color: #565959;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .info-item span {
        font-size: 14px;
        color: #0f1111;
        font-weight: 500;
    }
    
    /* Customer Notes Box */
    .notes-box {
        margin-top: 20px;
        padding: 16px;
        background: linear-gradient(135deg, #f8f9fa 0%, #fff8e8 100%);
        border-radius: 10px;
        border-left: 4px solid #febd69;
    }
    .notes-box label {
        font-size: 11px;
        color: #565959;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 8px;
        display: block;
    }
    .notes-box p {
        color: #0f1111;
        font-size: 14px;
        line-height: 1.5;
    }
    
    /* Images Grid */
    .images-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 10px;
        margin-top: 16px;
    }
    .images-grid img {
        width: 100%;
        aspect-ratio: 1;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: transform 0.2s;
        border: 2px solid transparent;
    }
    .images-grid img:hover {
        transform: scale(1.05);
        border-color: #febd69;
    }
    
    /* Messages Section - Modern Chat UI */
    .messages-container {
        height: 320px;
        overflow-y: auto;
        padding: 16px;
        background: linear-gradient(180deg, #f8f9fa 0%, #fff 100%);
        border-radius: 12px;
        margin-bottom: 16px;
    }
    .messages-container::-webkit-scrollbar { width: 6px; }
    .messages-container::-webkit-scrollbar-track { background: #f1f1f1; border-radius: 3px; }
    .messages-container::-webkit-scrollbar-thumb { background: #ccc; border-radius: 3px; }
    
    .message-bubble {
        max-width: 85%;
        padding: 12px 16px;
        border-radius: 18px;
        margin-bottom: 12px;
        position: relative;
        animation: fadeInUp 0.3s ease;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .message-bubble.customer {
        background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
        margin-left: auto;
        border-bottom-right-radius: 6px;
    }
    .message-bubble.seller {
        background: linear-gradient(135deg, #fff 0%, #f5f5f5 100%);
        margin-right: auto;
        border-bottom-left-radius: 6px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }
    
    .message-header {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 6px;
    }
    .message-avatar {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 600;
    }
    .message-bubble.customer .message-avatar {
        background: linear-gradient(135deg, #1976d2, #42a5f5);
        color: #fff;
    }
    .message-bubble.seller .message-avatar {
        background: linear-gradient(135deg, #ff9900, #febd69);
        color: #232f3e;
    }
    .message-sender {
        font-size: 12px;
        font-weight: 600;
        color: #374151;
    }
    .message-text {
        font-size: 14px;
        color: #1e293b;
        line-height: 1.5;
    }
    .message-time {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    .message-time i { font-size: 10px; }
    
    .no-messages {
        text-align: center;
        padding: 40px 20px;
        color: #94a3b8;
    }
    .no-messages i {
        font-size: 48px;
        margin-bottom: 12px;
        opacity: 0.5;
    }
    
    .message-form {
        display: flex;
        gap: 10px;
        background: #f8f9fa;
        padding: 12px;
        border-radius: 12px;
    }
    .message-form input {
        flex: 1;
        padding: 12px 18px;
        border: 2px solid #e5e7eb;
        border-radius: 25px;
        font-size: 14px;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .message-form input:focus {
        border-color: #febd69;
        outline: none;
        box-shadow: 0 0 0 3px rgba(254, 189, 105, 0.2);
    }
    .message-form button {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff9900 0%, #febd69 100%);
        border: none;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #232f3e;
        font-size: 18px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .message-form button:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(255, 153, 0, 0.4);
    }
    
    /* Buttons */
    .btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 20px;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        text-decoration: none;
        transition: all 0.2s;
        border: none;
        cursor: pointer;
        width: 100%;
    }
    .btn-success {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: #fff;
    }
    .btn-success:hover { box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4); transform: translateY(-1px); }
    .btn-danger {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        color: #dc2626;
    }
    .btn-danger:hover { background: linear-gradient(135deg, #fecaca 0%, #fca5a5 100%); }
    .btn-primary {
        background: linear-gradient(135deg, #ff9900 0%, #febd69 100%);
        color: #232f3e;
    }
    .btn-primary:hover { box-shadow: 0 4px 12px rgba(255, 153, 0, 0.4); transform: translateY(-1px); }
    
    /* Form Groups */
    .form-group { margin-bottom: 16px; }
    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 500;
        color: #374151;
        margin-bottom: 6px;
    }
    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 10px 14px;
        border: 1.5px solid #e5e7eb;
        border-radius: 8px;
        font-size: 14px;
        transition: border-color 0.2s;
    }
    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #febd69;
        outline: none;
    }
    
    /* Timeline */
    .timeline-mini { margin-top: 8px; }
    .timeline-step {
        display: flex;
        align-items: flex-start;
        gap: 14px;
        padding: 10px 0;
        position: relative;
    }
    .timeline-step:not(:last-child)::after {
        content: '';
        position: absolute;
        left: 9px;
        top: 34px;
        bottom: 0;
        width: 2px;
        background: #e5e7eb;
    }
    .timeline-step .dot {
        width: 20px;
        height: 20px;
        border-radius: 50%;
        background: #e5e7eb;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .timeline-step .dot.active {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
    }
    .timeline-step .dot i { font-size: 9px; color: #fff; }
    .timeline-step .label { color: #94a3b8; font-size: 13px; }
    .timeline-step .label.active { color: #0f1111; font-weight: 500; }
    .timeline-step .label small { color: #94a3b8; display: block; font-size: 11px; margin-top: 2px; }
    
    /* Alert Messages */
    .alert {
        padding: 14px 20px;
        border-radius: 10px;
        margin-bottom: 20px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .alert-success { background: #d1fae5; color: #065f46; }
    .alert-error { background: #fee2e2; color: #991b1b; }
    .alert-info { background: #dbeafe; color: #1e40af; }
    .alert-warning { background: #fef3c7; color: #92400e; }
    
    @media (max-width: 1100px) {
        .return-grid { grid-template-columns: 1fr; }
    }
    @media (max-width: 768px) {
        .info-grid { grid-template-columns: 1fr; }
    }
</style>
@endsection

@section('content')
<div class="page-header">
    <div>
        <a href="{{ route('seller.returns.index') }}" class="back-link">
            <i class="fas fa-arrow-left"></i> Back to Returns
        </a>
        <h1 class="page-title">Return {{ $return->return_number }}</h1>
    </div>
    <span class="status-badge" style="background:{{ $return->status_color }}22;color:{{ $return->status_color }};">
        {{ $return->status_label }}
    </span>
</div>

@if(session('success'))
    <div class="alert alert-success">
        <i class="fas fa-check-circle"></i> {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="alert alert-error">
        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
    </div>
@endif

@if(session('info'))
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> {{ session('info') }}
    </div>
@endif

@if(session('warning'))
    <div class="alert alert-warning">
        <i class="fas fa-exclamation-triangle"></i> {{ session('warning') }}
    </div>
@endif

<div class="return-grid">
    <div>
        <!-- Return Details Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-box-open"></i> Return Details</h3>
            </div>
            <div class="card-body">
                <div class="product-summary">
                    @php
                        $placeholder = 'https://placehold.co/90x90?text=No+Image';
                        $img = $return->product->img_path ?? null;
                        
                        // If it's a local path, build a public URL
                        if ($img && !preg_match('/^https?:\/\//', $img)) {
                            $img = str_starts_with($img, 'http') ? $img : asset('storage/' . ltrim($img, '/'));
                        }
                        
                        // Default fallback
                        if (!$img) {
                            $img = 'https://m.media-amazon.com/images/I/41-a+x5eB+L._SX342_SY445_.jpg';
                        }
                    @endphp
                    <img src="{{ $img }}" alt="{{ $return->product->name ?? '' }}" referrerpolicy="no-referrer" onerror="this.onerror=null;this.src='{{ $placeholder }}'">
                    <div>
                        <h4>{{ $return->product->name ?? 'Product' }}</h4>
                        <p>Order #{{ $return->order_id }} • SKU: {{ $return->product->sku ?? 'N/A' }}</p>
                        <p>Quantity: {{ $return->quantity }}</p>
                        <div class="price">₹{{ number_format($return->refund_amount, 0) }}</div>
                    </div>
                </div>
                
                <div class="info-grid">
                    <div class="info-item">
                        <label>Customer</label>
                        <span>{{ $return->customer->name ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Email</label>
                        <span>{{ $return->customer->email ?? 'N/A' }}</span>
                    </div>
                    <div class="info-item">
                        <label>Return Reason</label>
                        <span>{{ $return->reason_label }}</span>
                    </div>
                    <div class="info-item">
                        <label>Requested On</label>
                        <span>{{ $return->created_at->format('M d, Y h:i A') }}</span>
                    </div>
                    <div class="info-item">
                        <label>Pickup Address</label>
                        <span>{{ $return->pickup_address ?? 'Not set' }}</span>
                    </div>
                    @if($return->tracking_number)
                    <div class="info-item">
                        <label>Tracking #</label>
                        <span>{{ $return->tracking_number }}</span>
                    </div>
                    @endif
                </div>
                
                @if($return->reason_details)
                <div class="notes-box">
                    <label><i class="fas fa-comment-alt"></i> Customer Notes</label>
                    <p>{{ $return->reason_details }}</p>
                </div>
                @endif
                
                @if($return->images && count($return->images) > 0)
                <div class="images-grid">
                    @foreach($return->images as $image)
                        <img src="{{ asset('storage/' . $image) }}" alt="Return image" onclick="window.open(this.src, '_blank')">
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        
        <!-- Messages Card -->
        <div class="card">
            <div class="card-header">
                <h3><i class="fas fa-comments"></i> Messages</h3>
            </div>
            <div class="card-body">
                <div class="messages-container" id="messagesContainer">
                    @forelse($return->messages as $message)
                        <div class="message-bubble {{ $message->sender_type }}">
                            <div class="message-header">
                                <div class="message-avatar">
                                    @if($message->sender_type === 'customer')
                                        <i class="fas fa-user"></i>
                                    @else
                                        <i class="fas fa-store"></i>
                                    @endif
                                </div>
                                <span class="message-sender">{{ $message->sender_name }}</span>
                            </div>
                            <div class="message-text">{{ $message->message }}</div>
                            <div class="message-time">
                                <i class="fas fa-clock"></i>
                                {{ $message->created_at->format('M d, h:i A') }}
                            </div>
                        </div>
                    @empty
                        <div class="no-messages">
                            <i class="fas fa-comments"></i>
                            <p>No messages yet</p>
                            <small>Start the conversation with your customer</small>
                        </div>
                    @endforelse
                </div>
                
                <form action="{{ route('seller.returns.message', $return->id) }}" method="POST" class="message-form">
                    @csrf
                    <input type="text" name="message" placeholder="Type your message..." required>
                    <button type="submit" title="Send Message"><i class="fas fa-paper-plane"></i></button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Sidebar Actions -->
    <div>
        <!-- Quick Actions based on status -->
        @if($return->status === 'pending')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-clipboard-check"></i> Review Return</h3></div>
            <div class="card-body">
                <form action="{{ route('seller.returns.approve', $return->id) }}" method="POST" style="margin-bottom:16px;">
                    @csrf
                    <div class="form-group">
                        <label>Notes (optional)</label>
                        <textarea name="seller_notes" rows="2" placeholder="Add notes for customer..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-check"></i> Approve Return</button>
                </form>
                <form action="{{ route('seller.returns.reject', $return->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Rejection Reason</label>
                        <textarea name="rejection_reason" rows="2" placeholder="Why are you rejecting this return?"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-times"></i> Reject Return</button>
                </form>
            </div>
        </div>
        @endif
        
        @if($return->status === 'approved')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-truck"></i> Schedule Pickup</h3></div>
            <div class="card-body">
                <form action="{{ route('seller.returns.schedule-pickup', $return->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Courier Name</label>
                        <input type="text" name="courier_name" placeholder="e.g., BlueDart, DTDC" required>
                    </div>
                    <div class="form-group">
                        <label>Pickup Date</label>
                        <input type="date" name="pickup_date" min="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="form-group">
                        <label>Tracking Number <span style="font-size:11px;color:#6b7280;font-weight:400;">(Auto-generated if left empty)</span></label>
                        <input type="text" name="tracking_number" placeholder="Leave empty for auto-generation" style="font-family: 'Courier New', monospace;">
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="fas fa-truck"></i> Schedule Pickup</button>
                </form>
            </div>
        </div>
        @endif
        
        @if($return->status === 'pickup_scheduled')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-box"></i> Update Status</h3></div>
            <div class="card-body">
                <form action="{{ route('seller.returns.mark-picked-up', $return->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="fas fa-box"></i> Mark as Picked Up</button>
                </form>
            </div>
        </div>
        @endif
        
        @if($return->status === 'picked_up')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-inbox"></i> Update Status</h3></div>
            <div class="card-body">
                <form action="{{ route('seller.returns.mark-received', $return->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-primary"><i class="fas fa-inbox"></i> Mark as Received</button>
                </form>
            </div>
        </div>
        @endif
        
        @if($return->status === 'received')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-search"></i> Inspection & Refund</h3></div>
            <div class="card-body">
                <form action="{{ route('seller.returns.complete-inspection', $return->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Inspection Result</label>
                        <select name="inspection_result" required onchange="toggleRefundAmount(this)">
                            <option value="">Select...</option>
                            <option value="approve_refund">Approve Full Refund</option>
                            <option value="partial_refund">Partial Refund</option>
                            <option value="reject_refund">Reject Refund</option>
                        </select>
                    </div>
                    <div class="form-group" id="partial-amount" style="display:none;">
                        <label>Refund Amount</label>
                        <input type="number" name="refund_amount" placeholder="Enter amount" step="0.01" max="{{ $return->refund_amount }}">
                    </div>
                    <div class="form-group">
                        <label>Inspection Notes</label>
                        <textarea name="inspection_notes" rows="2" placeholder="Describe item condition..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-clipboard-check"></i> Complete Inspection</button>
                </form>
            </div>
        </div>
        @endif
        
        @if($return->status === 'inspected')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-rupee-sign"></i> Initiate Refund</h3></div>
            <div class="card-body">
                <p style="font-size:14px;color:#565959;margin-bottom:16px;">
                    Refund Amount: <strong style="color:#059669;font-size:18px;">₹{{ number_format($return->refund_amount, 0) }}</strong>
                </p>
                <form action="{{ route('seller.returns.initiate-refund', $return->id) }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Refund Method</label>
                        <select name="refund_method" required>
                            <option value="original_payment">Original Payment Method</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="upi">UPI</option>
                            <option value="store_credit">Store Credit</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-rupee-sign"></i> Request Refund from Admin</button>
                </form>
                <p style="font-size:12px;color:#6b7280;margin-top:10px;">This will send a refund request to the admin. Admin will process the refund via Razorpay.</p>
            </div>
        </div>
        @endif
        
        @if($return->status === 'refund_initiated' && $return->refund)
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-clock"></i> Refund Status</h3></div>
            <div class="card-body">
                <div style="padding:16px;border-radius:8px;background:#fef3c7;border:1px solid #f59e0b;margin-bottom:12px;">
                    <p style="font-size:14px;color:#92400e;margin:0;"><strong>Refund request sent to admin</strong></p>
                    <p style="font-size:13px;color:#92400e;margin:4px 0 0;">Amount: <strong>₹{{ number_format($return->refund->amount, 2) }}</strong> | Method: {{ $return->refund->refund_method_label }}</p>
                    <p style="font-size:12px;color:#92400e;margin:4px 0 0;">Status: <strong>{{ $return->refund->status_label }}</strong> — Waiting for admin to process via Razorpay.</p>
                </div>
            </div>
        </div>
        @endif

        @if($return->status === 'refund_completed' && $return->refund && $return->refund->status === 'completed')
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-check-circle" style="color:#059669;"></i> Refund Completed</h3></div>
            <div class="card-body">
                <div style="padding:16px;border-radius:8px;background:#d1fae5;border:1px solid #059669;">
                    <p style="font-size:15px;color:#065f46;margin:0;"><strong>Payment refund is successful!</strong></p>
                    <p style="font-size:14px;color:#065f46;margin:8px 0 0;">Amount: <strong>₹{{ number_format($return->refund->amount, 2) }}</strong></p>
                    @if($return->refund->razorpay_refund_id)
                    <p style="font-size:14px;color:#065f46;margin:4px 0 0;">Razorpay Reference ID: <strong style="font-family:monospace;letter-spacing:0.5px;">{{ $return->refund->razorpay_refund_id }}</strong></p>
                    @endif
                    @if($return->refund->transaction_id)
                    <p style="font-size:14px;color:#065f46;margin:4px 0 0;">Transaction ID: <strong style="font-family:monospace;">{{ $return->refund->transaction_id }}</strong></p>
                    @endif
                    <p style="font-size:12px;color:#065f46;margin:6px 0 0;">Completed on {{ $return->refund->completed_at->format('d M Y, h:i A') }}</p>
                </div>
            </div>
        </div>
        @endif
        
        <!-- Timeline Card -->
        <div class="card">
            <div class="card-header"><h3><i class="fas fa-history"></i> Return Progress</h3></div>
            <div class="card-body">
                <div class="timeline-mini">
                    @php
                        $steps = [
                            ['key' => 'pending', 'label' => 'Return Requested', 'date' => $return->created_at],
                            ['key' => 'approved', 'label' => 'Approved', 'date' => $return->approved_at],
                            ['key' => 'pickup_scheduled', 'label' => 'Pickup Scheduled', 'date' => $return->pickup_scheduled_at],
                            ['key' => 'picked_up', 'label' => 'Picked Up', 'date' => $return->picked_up_at],
                            ['key' => 'received', 'label' => 'Received', 'date' => $return->received_at],
                            ['key' => 'refund_completed', 'label' => 'Refund Completed', 'date' => $return->refund_completed_at],
                        ];
                    @endphp
                    @foreach($steps as $step)
                        <div class="timeline-step">
                            <div class="dot {{ $step['date'] ? 'active' : '' }}">
                                @if($step['date'])<i class="fas fa-check"></i>@endif
                            </div>
                            <div class="label {{ $step['date'] ? 'active' : '' }}">
                                {{ $step['label'] }}
                                @if($step['date'])
                                    <small>{{ $step['date']->format('M d, h:i A') }}</small>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra_scripts')
    function toggleRefundAmount(select) {
        const partialDiv = document.getElementById('partial-amount');
        partialDiv.style.display = select.value === 'partial_refund' ? 'block' : 'none';
    }
    
    // Scroll messages to bottom
    const messagesContainer = document.getElementById('messagesContainer');
    if (messagesContainer) {
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    // Auto-refresh page every 10 seconds to show new messages and status updates
    let lastMessageCount = {{ count($return->messages) }};
    setInterval(function() {
        fetch('{{ route('seller.returns.show', $return->id) }}', {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newMessages = doc.getElementById('messagesContainer');
            const currentMessages = document.getElementById('messagesContainer');
            
            if (newMessages && currentMessages) {
                const newCount = newMessages.children.length;
                if (newCount !== lastMessageCount) {
                    currentMessages.innerHTML = newMessages.innerHTML;
                    currentMessages.scrollTop = currentMessages.scrollHeight;
                    lastMessageCount = newCount;
                }
            }
            
            // Check for status changes and reload page if needed
            const newStatusBadge = doc.querySelector('.status-badge');
            const currentStatusBadge = document.querySelector('.status-badge');
            if (newStatusBadge && currentStatusBadge && 
                newStatusBadge.textContent.trim() !== currentStatusBadge.textContent.trim()) {
                location.reload();
            }
        })
        .catch(err => console.log('Auto-refresh error:', err));
    }, 10000); // Check every 10 seconds
@endsection
