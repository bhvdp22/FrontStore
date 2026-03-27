<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Refund - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: #f9fafb; color: #111827; min-height: 100vh; }
        h1, h2, h3, h4 { font-family: 'Montserrat', sans-serif; }
        a { color: #3b82f6; text-decoration: none; }
        a:hover { text-decoration: underline; }
    </style>
</head>
<body>

@include('shop.partials.navbar')

<div style="max-width:700px;margin:40px auto;padding:0 16px;">
    <a href="{{ route('returns.show', $return->id) }}" style="color:#3b82f6;font-size:14px;">&larr; Back to Return Details</a>
    
    <h2 style="font-size:22px;font-weight:700;margin:16px 0 8px;">Track Refund</h2>
    <p style="font-size:14px;color:#6b7280;margin-bottom:24px;">Return #{{ $return->return_number }}</p>

    @if($return->refund)
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;margin-bottom:24px;">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;">
            <div>
                <p style="font-size:12px;color:#9ca3af;margin:0;">Refund Number</p>
                <p style="font-size:14px;font-weight:600;margin:4px 0 0;">{{ $return->refund->refund_number }}</p>
            </div>
            <div>
                <p style="font-size:12px;color:#9ca3af;margin:0;">Amount</p>
                <p style="font-size:18px;font-weight:700;color:#059669;margin:4px 0 0;">₹{{ number_format($return->refund->amount, 2) }}</p>
            </div>
            <div>
                <p style="font-size:12px;color:#9ca3af;margin:0;">Method</p>
                <p style="font-size:14px;margin:4px 0 0;">{{ $return->refund->refund_method_label }}</p>
            </div>
            <div>
                <p style="font-size:12px;color:#9ca3af;margin:0;">Status</p>
                <p style="font-size:14px;font-weight:600;margin:4px 0 0;color:{{ $return->refund->status_color }};">{{ $return->refund->status_label }}</p>
            </div>
        </div>

        @if($return->refund->status === 'completed')
        <div style="padding:16px;border-radius:10px;background:#d1fae5;border:1px solid #059669;">
            <p style="font-size:16px;font-weight:700;color:#065f46;margin:0;"><i class="fas fa-check-circle"></i> Payment Refund Successful!</p>
            <p style="font-size:13px;color:#065f46;margin:8px 0 0;">Your refund has been processed successfully.</p>
            
            @if($return->refund->razorpay_refund_id)
            <div style="margin-top:12px;padding:12px;background:#ecfdf5;border-radius:8px;">
                <p style="font-size:12px;color:#065f46;margin:0;">Razorpay Reference ID</p>
                <p style="font-size:16px;font-weight:700;color:#059669;margin:4px 0 0;font-family:monospace;letter-spacing:1px;">{{ $return->refund->razorpay_refund_id }}</p>
            </div>
            @endif

            @if($return->refund->transaction_id && $return->refund->transaction_id !== $return->refund->razorpay_refund_id)
            <div style="margin-top:8px;">
                <p style="font-size:12px;color:#065f46;margin:0;">Transaction ID: <strong style="font-family:monospace;">{{ $return->refund->transaction_id }}</strong></p>
            </div>
            @endif

            @if($return->refund->completed_at)
            <p style="font-size:12px;color:#065f46;margin:8px 0 0;">Completed on {{ $return->refund->completed_at->format('d M Y, h:i A') }}</p>
            @endif
        </div>
        @elseif($return->refund->status === 'pending')
        <div style="padding:16px;border-radius:10px;background:#fef3c7;border:1px solid #f59e0b;">
            <p style="font-size:15px;font-weight:600;color:#92400e;margin:0;"><i class="fas fa-clock"></i> Refund Processing</p>
            <p style="font-size:13px;color:#92400e;margin:6px 0 0;">Your refund request is being reviewed by our admin team. You will be notified once the refund is processed.</p>
        </div>
        @elseif($return->refund->status === 'failed')
        <div style="padding:16px;border-radius:10px;background:#fef2f2;border:1px solid #ef4444;">
            <p style="font-size:15px;font-weight:600;color:#991b1b;margin:0;"><i class="fas fa-exclamation-circle"></i> Refund Failed</p>
            @if($return->refund->failure_reason)
            <p style="font-size:13px;color:#991b1b;margin:6px 0 0;">Reason: {{ $return->refund->failure_reason }}</p>
            @endif
            <p style="font-size:12px;color:#991b1b;margin:6px 0 0;">Please contact support for assistance.</p>
        </div>
        @endif

        @if($return->refund->initiated_at)
        <div style="margin-top:20px;border-top:1px solid #e5e7eb;padding-top:16px;">
            <p style="font-size:12px;color:#9ca3af;margin:0;">Timeline</p>
            <div style="margin-top:8px;">
                <p style="font-size:13px;color:#374151;margin:4px 0;"><span style="color:#9ca3af;">Initiated:</span> {{ $return->refund->initiated_at->format('d M Y, h:i A') }}</p>
                @if($return->refund->completed_at)
                <p style="font-size:13px;color:#374151;margin:4px 0;"><span style="color:#9ca3af;">Completed:</span> {{ $return->refund->completed_at->format('d M Y, h:i A') }}</p>
                @endif
                @if($return->refund->failed_at)
                <p style="font-size:13px;color:#374151;margin:4px 0;"><span style="color:#9ca3af;">Failed:</span> {{ $return->refund->failed_at->format('d M Y, h:i A') }}</p>
                @endif
            </div>
        </div>
        @endif
    </div>
    @else
    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:24px;text-align:center;">
        <p style="font-size:14px;color:#6b7280;">Refund has not been initiated yet.</p>
    </div>
    @endif

    <div style="background:#fff;border:1px solid #e5e7eb;border-radius:12px;padding:16px;">
        <p style="font-size:13px;color:#6b7280;line-height:1.7;margin:0;">
            Need policy details?
            <a href="{{ route('page.return-policy') }}" style="font-weight:600;">Return Policy</a> |
            <a href="{{ route('page.refund-policy') }}" style="font-weight:600;">Refund Policy</a> |
            <a href="{{ route('page.privacy-policy') }}" style="font-weight:600;">Privacy Policy</a>
        </p>
    </div>
</div>

@include('shop.partials.footer')

</body>
</html>
