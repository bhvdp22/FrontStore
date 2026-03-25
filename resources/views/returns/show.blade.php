<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Details - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; padding: 20px; }
        
        .page-header { margin-bottom: 24px; }
        .back-link { display: inline-flex; align-items: center; gap: 8px; color: #007185; text-decoration: none; font-size: 14px; margin-bottom: 16px; }
        .back-link:hover { text-decoration: underline; }
        .page-title { font-size: 28px; font-weight: 700; color: #232f3e; display: flex; align-items: center; gap: 12px; }
        .page-title i { color: #febd69; }
        
        .return-grid { display: grid; grid-template-columns: 1fr 350px; gap: 24px; }
        
        .main-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 24px;
            background: #f8f9fa;
            border-bottom: 1px solid #e5e7eb;
            flex-wrap: wrap;
            gap: 12px;
        }
        .card-header h3 { font-size: 18px; color: #111827; }
        .status-badge {
            padding: 8px 16px;
            border-radius: 25px;
            font-size: 13px;
            font-weight: 600;
        }
        
        .card-body { padding: 24px; }
        
        .product-info {
            display: flex;
            gap: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e5e7eb;
            margin-bottom: 20px;
        }
        .product-info img {
            width: 100px;
            height: 100px;
            border-radius: 10px;
            object-fit: cover;
            border: 1px solid #e5e7eb;
        }
        .product-info h4 { font-size: 16px; font-weight: 600; color: #111827; margin-bottom: 6px; }
        .product-info p { font-size: 14px; color: #6b7280; margin-bottom: 4px; }
        
        .info-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        .info-item label {
            display: block;
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 4px;
            text-transform: uppercase;
        }
        .info-item span {
            font-size: 15px;
            color: #111827;
            font-weight: 500;
        }
        
        .timeline {
            margin-top: 24px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
        }
        .timeline h4 { font-size: 16px; font-weight: 600; color: #374151; margin-bottom: 16px; }
        .timeline-item {
            display: flex;
            gap: 16px;
            padding: 12px 0;
            position: relative;
        }
        .timeline-item:not(:last-child)::after {
            content: '';
            position: absolute;
            left: 11px;
            top: 36px;
            bottom: 0;
            width: 2px;
            background: #e5e7eb;
        }
        .timeline-dot {
            width: 24px;
            height: 24px;
            border-radius: 50%;
            background: #e5e7eb;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .timeline-dot.active { background: #10b981; }
        .timeline-dot i { font-size: 10px; color: #fff; }
        .timeline-content h5 { font-size: 14px; font-weight: 600; color: #111827; }
        .timeline-content p { font-size: 13px; color: #6b7280; }
        
        .sidebar { display: flex; flex-direction: column; gap: 20px; }
        
        .sidebar-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            padding: 24px;
        }
        .sidebar-card h4 {
            font-size: 16px;
            font-weight: 600;
            color: #374151;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .sidebar-card h4 i { color: #febd69; }
        
        .refund-amount {
            font-size: 32px;
            font-weight: 700;
            color: #059669;
            text-align: center;
            margin-bottom: 16px;
        }
        .refund-status { text-align: center; font-size: 14px; color: #6b7280; }
        
        /* Messages Section - Modern Chat UI */
        .messages-container {
            height: 280px;
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
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
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
            padding: 30px 20px;
            color: #94a3b8;
        }
        .no-messages i {
            font-size: 40px;
            margin-bottom: 10px;
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
        
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 12px 20px;
            border-radius: 25px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            width: 100%;
            font-size: 14px;
        }
        .btn-danger { background: #fee2e2; color: #dc2626; }
        .btn-danger:hover { background: #fecaca; }
        
        .images-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 16px;
        }
        .images-grid img {
            width: 100%;
            aspect-ratio: 1;
            object-fit: cover;
            border-radius: 8px;
            cursor: pointer;
        }
        
        @media (max-width: 900px) {
            .return-grid { grid-template-columns: 1fr; }
            .info-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('shop.partials.navbar')
    
    <div class="container">
        <div class="page-header">
            <a href="{{ route('returns.index') }}" class="back-link">
                <i class="fas fa-arrow-left"></i> Back to My Returns
            </a>
            <h1 class="page-title"><i class="fas fa-undo-alt"></i> Return {{ $return->return_number }}</h1>
        </div>
        
        @if(session('success'))
            <div style="background:#d1fae5;color:#065f46;padding:14px 20px;border-radius:8px;margin-bottom:20px;font-weight:500;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif
        
        <div class="return-grid">
            <div class="main-card">
                <div class="card-header">
                    <h3>Return Details</h3>
                    <span class="status-badge" style="background:{{ $return->status_color }}22;color:{{ $return->status_color }};">
                        {{ $return->status_label }}
                    </span>
                </div>
                <div class="card-body">
                    <div class="product-info">
                        @php
                            $placeholder = 'https://placehold.co/100x100?text=No+Image';
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
                            <p>Order #{{ $return->order_id }}</p>
                            <p>Quantity: {{ $return->quantity }}</p>
                            <p>Sold by: {{ $return->seller->business_name ?? $return->seller->name ?? 'Seller' }}</p>
                        </div>
                    </div>
                    
                    <div class="info-grid">
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
                            <label>Tracking Number</label>
                            <span>{{ $return->tracking_number }}</span>
                        </div>
                        @endif
                    </div>
                    
                    @if($return->reason_details)
                    <div style="margin-top:20px;padding:16px;background:#f9fafb;border-radius:10px;">
                        <label style="font-size:12px;color:#6b7280;text-transform:uppercase;margin-bottom:6px;display:block;">Additional Details</label>
                        <p style="color:#374151;font-size:14px;">{{ $return->reason_details }}</p>
                    </div>
                    @endif
                    
                    @if($return->images && count($return->images) > 0)
                    <div class="images-grid">
                        @foreach($return->images as $image)
                            <img src="{{ asset('storage/' . $image) }}" alt="Return image">
                        @endforeach
                    </div>
                    @endif
                    
                    <div class="timeline">
                        <h4><i class="fas fa-history"></i> Return Timeline</h4>
                        
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Return Requested</h5>
                                <p>{{ $return->created_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        
                        @if($return->approved_at)
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Return Approved</h5>
                                <p>{{ $return->approved_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($return->rejected_at)
                        <div class="timeline-item">
                            <div class="timeline-dot" style="background:#ef4444;"><i class="fas fa-times"></i></div>
                            <div class="timeline-content">
                                <h5>Return Rejected</h5>
                                <p>{{ $return->rejected_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($return->pickup_scheduled_at)
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Pickup Scheduled</h5>
                                <p>{{ $return->pickup_scheduled_at->format('M d, Y') }} - {{ $return->courier_name }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($return->picked_up_at)
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Item Picked Up</h5>
                                <p>{{ $return->picked_up_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($return->received_at)
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Received by Seller</h5>
                                <p>{{ $return->received_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($return->refund_initiated_at)
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Refund Initiated</h5>
                                <p>{{ $return->refund_initiated_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                        
                        @if($return->refund_completed_at)
                        <div class="timeline-item">
                            <div class="timeline-dot active"><i class="fas fa-check"></i></div>
                            <div class="timeline-content">
                                <h5>Refund Completed</h5>
                                <p>{{ $return->refund_completed_at->format('M d, Y h:i A') }}</p>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
            
            <div class="sidebar">
                <div class="sidebar-card">
                    <h4><i class="fas fa-rupee-sign"></i> Refund Amount</h4>
                    <div class="refund-amount">₹{{ number_format($return->refund_amount, 0) }}</div>
                    <div class="refund-status">
                        @if($return->refund)
                            {{ $return->refund->status_label }}
                            @if($return->refund->status === 'completed')
                                <br><small>Completed on {{ $return->refund->completed_at->format('M d, Y') }}</small>
                                @if($return->refund->razorpay_refund_id)
                                <div style="margin-top:12px;padding:12px;border-radius:8px;background:#d1fae5;border:1px solid #059669;">
                                    <p style="font-size:13px;color:#065f46;margin:0;font-weight:600;">Payment Refund Successful</p>
                                    <p style="font-size:12px;color:#065f46;margin:4px 0 0;">Razorpay Reference ID:</p>
                                    <p style="font-size:13px;color:#065f46;margin:2px 0 0;font-family:monospace;font-weight:700;letter-spacing:0.5px;">{{ $return->refund->razorpay_refund_id }}</p>
                                </div>
                                @endif
                                @if($return->refund->transaction_id && $return->refund->transaction_id !== $return->refund->razorpay_refund_id)
                                <div style="margin-top:8px;">
                                    <small style="color:#6b7280;">Transaction ID: <strong style="font-family:monospace;">{{ $return->refund->transaction_id }}</strong></small>
                                </div>
                                @endif
                            @elseif($return->refund->status === 'pending')
                                <br><small style="color:#f59e0b;">Refund is being processed...</small>
                            @endif
                        @else
                            Pending approval
                        @endif
                    </div>
                    
                    @if($return->canBeCancelled())
                    <form action="{{ route('returns.cancel', $return->id) }}" method="POST" style="margin-top:16px;" onsubmit="return confirm('Are you sure you want to cancel this return?');">
                        @csrf
                        <button type="submit" class="btn btn-danger">
                            <i class="fas fa-times"></i> Cancel Return
                        </button>
                    </form>
                    @endif
                </div>
                
                <div class="sidebar-card">
                    <h4><i class="fas fa-comments"></i> Messages</h4>
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
                            </div>
                        @endforelse
                    </div>
                    
                    <form action="{{ route('returns.message', $return->id) }}" method="POST" class="message-form">
                        @csrf
                        <input type="text" name="message" placeholder="Type a message..." required>
                        <button type="submit" title="Send"><i class="fas fa-paper-plane"></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    @include('shop.partials.footer')
    
    <script>
        // Scroll messages to bottom on page load
        const messagesContainer = document.getElementById('messagesContainer');
        if (messagesContainer) {
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }

        // Auto-refresh page every 10 seconds to show new messages and status updates
        let lastMessageCount = {{ count($return->messages) }};
        setInterval(function() {
            fetch('{{ route('returns.show', $return->id) }}', {
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
    </script>
</body>
</html>
