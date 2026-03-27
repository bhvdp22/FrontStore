<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Refund Policy - FrontStore</title>
    <meta name="description" content="FrontStore refund policy for multi-vendor orders, returns, cancellations, payment failures, and refund timelines.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); color: #111827; min-height: 100vh; }
        .container { max-width: 980px; margin: 0 auto; padding: 28px 18px; }
        .header-card { background: linear-gradient(135deg, #232f3e 0%, #36485e 100%); color: #fff; border-radius: 14px; padding: 30px 28px; margin-bottom: 24px; }
        .header-card h1 { font-size: 30px; display: flex; gap: 12px; align-items: center; margin-bottom: 8px; }
        .header-card h1 i { color: #febd69; }
        .header-card p { font-size: 14px; opacity: 0.9; }
        .policy-card { background: #fff; border-radius: 14px; box-shadow: 0 6px 22px rgba(0,0,0,0.08); padding: 28px; }
        h2 { font-family: 'Montserrat', sans-serif; font-size: 20px; color: #1f2937; margin: 22px 0 10px; border-bottom: 2px solid #febd69; display: inline-block; padding-bottom: 6px; }
        h2:first-of-type { margin-top: 0; }
        h3 { font-size: 16px; margin: 16px 0 8px; color: #374151; }
        p { font-size: 14.5px; line-height: 1.8; margin-bottom: 10px; color: #374151; }
        ul, ol { padding-left: 22px; margin-bottom: 12px; }
        li { font-size: 14.5px; line-height: 1.8; color: #374151; margin-bottom: 5px; }
        .note { background: #fff8e8; border-left: 4px solid #febd69; padding: 14px; border-radius: 8px; margin: 12px 0 18px; font-size: 14px; }
        .links { margin-top: 20px; display: flex; flex-wrap: wrap; gap: 14px; }
        .links a { color: #d97706; text-decoration: none; font-weight: 600; }
        .links a:hover { text-decoration: underline; }
        .footer-line { margin-top: 24px; padding-top: 14px; border-top: 1px solid #e5e7eb; color: #6b7280; font-size: 13px; }
        @media (max-width: 700px) {
            .header-card h1 { font-size: 24px; }
            .policy-card { padding: 18px; }
        }
    </style>
</head>
<body>
    @include('shop.partials.navbar')

    <div class="container">
        <div class="header-card">
            <h1><i class="fas fa-wallet"></i> Refund Policy</h1>
            <p>Clear and fair refund handling for customers and sellers across FrontStore marketplace orders.</p>
        </div>

        <div class="policy-card">
            <div class="note">
                FrontStore operates as a multi-vendor marketplace. Refund outcomes depend on order status, return eligibility, seller inspection, and admin processing controls.
            </div>

            <h2>1. Scope</h2>
            <p>This Refund Policy applies to orders placed on FrontStore and should be read together with our Return Policy.</p>

            <h2>2. When Refunds Are Allowed</h2>
            <ul>
                <li>Return request is approved and return workflow reaches an eligible refund stage.</li>
                <li>Seller inspection confirms full or partial refund eligibility.</li>
                <li>Paid order cancellation qualifies under platform cancellation rules.</li>
                <li>Verified failed payment or duplicate charge cases.</li>
                <li>Admin-approved exceptions for operational or compliance reasons.</li>
            </ul>

            <h2>3. Refund Approval Logic</h2>
            <p>Refunds are not issued automatically on request submission. Depending on order type, they may require one or more checks:</p>
            <ol>
                <li>Return request review by seller (approve or reject).</li>
                <li>Pickup/self-shipping completion where applicable.</li>
                <li>Seller receipt and inspection.</li>
                <li>Refund initiation and processing through allowed method.</li>
                <li>Admin confirmation for final completion where required.</li>
            </ol>

            <h2>4. Full and Partial Refunds</h2>
            <p>Refund amount is based on verified quantity and inspection findings.</p>
            <ul>
                <li>Full refund: item and claim match approved conditions.</li>
                <li>Partial refund: partial quantity return or partial value eligibility after inspection.</li>
                <li>No refund: rejected claims, non-returnable conditions, or misuse patterns.</li>
            </ul>

            <h2>5. Payment and Refund Methods</h2>
            <p>Refunds may be processed through one of the following methods, based on transaction data and admin decision:</p>
            <ul>
                <li>Original payment source (preferred for eligible online payments).</li>
                <li>Bank transfer.</li>
                <li>UPI.</li>
                <li>Store credit (if enabled in business rules).</li>
            </ul>

            <h2>6. Cancellation, Failed Payment, and Duplicate Charge Cases</h2>
            <h3>Order Cancellations</h3>
            <ul>
                <li>If no payment capture happened, no refund is applicable.</li>
                <li>If payment was captured and cancellation is valid, refund may be initiated to the original payment source where possible.</li>
            </ul>
            <h3>Failed or Duplicate Payments</h3>
            <ul>
                <li>Transactions are validated against order and payment records.</li>
                <li>Confirmed duplicate successful payments are refunded after verification.</li>
                <li>Gateway-side reconciliation may add processing time.</li>
            </ul>

            <h2>7. Refund Timelines</h2>
            <ul>
                <li>Internal review and processing: [Internal Review Time]</li>
                <li>Payment partner/bank settlement: [Refund Processing Time]</li>
            </ul>
            <p>Even after FrontStore marks a refund as completed, final credit timing depends on gateway, card network, UPI provider, or banking systems.</p>

            <h2>8. Non-Refundable Components</h2>
            <p>Where applicable and disclosed in advance, certain components may not be refundable, such as:</p>
            <ul>
                <li>[Non-Refundable Charges]</li>
                <li>Consumed service components.</li>
                <li>Policy-compliant deductions in misuse or abuse cases.</li>
            </ul>

            <h2>9. Fraud and Abuse Protection</h2>
            <p>FrontStore reserves the right to pause, investigate, reject, or reverse refund actions for suspicious behavior, manipulated evidence, account abuse, or payment fraud patterns.</p>

            <h2>10. Contact and Escalation</h2>
            <p>If your refund is delayed beyond expected timelines, contact support with your order and return/refund number.</p>
            <ul>
                <li>Support Email: [Support Email]</li>
                <li>Business Name: [Business Name]</li>
                <li>Escalation Window: [Escalation Window]</li>
            </ul>

            <div class="links">
                <a href="{{ route('page.return-policy') }}"><i class="fas fa-arrow-left"></i> Return Policy</a>
                <a href="{{ route('page.privacy-policy') }}">Privacy Policy</a>
                <a href="{{ route('page.disclaimer') }}">Disclaimer</a>
            </div>

            <div class="footer-line">Last Updated: [Last Updated Date]</div>
        </div>
    </div>

    @include('shop.partials.footer')
</body>
</html>