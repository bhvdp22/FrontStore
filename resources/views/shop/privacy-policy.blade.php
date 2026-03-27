<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Privacy Policy - FrontStore</title>
    <meta name="description" content="FrontStore privacy policy for customers and sellers in a multi-vendor marketplace.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f4f7fb 0%, #e8edf3 100%); color: #111827; min-height: 100vh; }
        .container { max-width: 1000px; margin: 0 auto; padding: 28px 18px; }
        .header-card { background: linear-gradient(135deg, #0f3a5c 0%, #1f4f75 100%); color: #fff; border-radius: 14px; padding: 30px 28px; margin-bottom: 24px; }
        .header-card h1 { font-size: 30px; display: flex; gap: 12px; align-items: center; margin-bottom: 8px; }
        .header-card h1 i { color: #7dd3fc; }
        .header-card p { font-size: 14px; color: #e7f4ff !important; opacity: 1; text-shadow: 0 1px 1px rgba(0,0,0,0.22); }
        .policy-card { background: #fff; border-radius: 14px; box-shadow: 0 6px 22px rgba(0,0,0,0.08); padding: 28px; }
        h2 { font-family: 'Montserrat', sans-serif; font-size: 20px; color: #1f2937; margin: 22px 0 10px; border-bottom: 2px solid #7dd3fc; display: inline-block; padding-bottom: 6px; }
        h2:first-of-type { margin-top: 0; }
        h3 { font-size: 16px; margin: 16px 0 8px; color: #374151; }
        p { font-size: 14.5px; line-height: 1.8; margin-bottom: 10px; color: #374151; }
        ul { padding-left: 22px; margin-bottom: 12px; }
        li { font-size: 14.5px; line-height: 1.8; color: #374151; margin-bottom: 5px; }
        .note { background: #eff6ff; border-left: 4px solid #3b82f6; padding: 14px; border-radius: 8px; margin: 12px 0 18px; font-size: 14px; }
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
            <h1><i class="fas fa-user-shield"></i> Privacy Policy</h1>
            <p>How FrontStore collects, uses, and protects customer and seller data across marketplace operations.</p>
        </div>

        <div class="policy-card">
            <div class="note">
                FrontStore is a multi-vendor marketplace. We collect and process data only to operate accounts, orders, payments, returns/refunds, and seller operations in a transparent and structured way.
            </div>

            <h2>1. Data We Collect</h2>
            <h3>Customer Data</h3>
            <ul>
                <li>Name, email, password, and phone number.</li>
                <li>Shipping addresses and saved address details.</li>
                <li>Order history, invoice details, return/refund records, and support messages.</li>
                <li>Product reviews/ratings and related moderation outcomes.</li>
            </ul>

            <h3>Seller Data</h3>
            <ul>
                <li>Name, contact details, and account credentials.</li>
                <li>Business profile information and tax/legal details (for example GSTIN/PAN/CIN where provided).</li>
                <li>Bank and payout details used for settlement workflows.</li>
                <li>Product listings, campaign/ad usage, earnings, deductions, and payout records.</li>
            </ul>

            <h3>Transaction and Payment Data</h3>
            <ul>
                <li>Order IDs, payment IDs, transaction references, and refund references.</li>
                <li>Payment status and verification records needed for settlement and dispute handling.</li>
                <li>Gateway-linked fields needed for processing (for example [Payment Gateway Name]).</li>
            </ul>

            <h3>Technical and Session Data</h3>
            <ul>
                <li>Session and cookie data required for login and cart continuity.</li>
                <li>Operational logs and error diagnostics for platform stability.</li>
                <li>Basic request/device metadata generated during website use.</li>
            </ul>

            <h2>2. Why We Use Data</h2>
            <ul>
                <li>Account creation, authentication, and profile management.</li>
                <li>Order placement, fulfillment, shipping, and invoice generation.</li>
                <li>Return/refund processing and dispute resolution.</li>
                <li>Seller payouts, fee/commission accounting, and reporting.</li>
                <li>Security checks, fraud prevention, and policy misuse monitoring.</li>
                <li>Regulatory, tax, and legal compliance obligations.</li>
            </ul>

            <h2>3. How Data Is Shared</h2>
            <ul>
                <li>With sellers for order fulfillment and post-order service on their own orders.</li>
                <li>With payment gateways/processors for authorization, verification, refunds, and reconciliation.</li>
                <li>With logistics/delivery partners where shipment or pickup support is enabled.</li>
                <li>With service providers supporting hosting, communication, storage, and system operations.</li>
                <li>With legal authorities where disclosure is required by law.</li>
            </ul>
            <p>FrontStore does not claim unverified certifications or guarantees not supported by platform configuration.</p>

            <h2>4. Cookies and Sessions</h2>
            <p>We use cookies and session technologies to maintain sign-in state, secure transactions, remember cart and workflow context, and improve service reliability.</p>
            <ul>
                <li>Cookie Consent Method: [Cookie Consent Mechanism]</li>
                <li>Cookie/Session Retention: [Cookie Retention Duration]</li>
            </ul>

            <h2>5. Data Retention</h2>
            <p>Data is retained only for operational, legal, accounting, fraud prevention, and dispute-resolution purposes, based on category and business/legal requirements.</p>
            <ul>
                <li>Retention Schedule: [Data Retention Policy by Category]</li>
            </ul>

            <h2>6. User Rights</h2>
            <p>Subject to law and verification, users may request access, correction, deletion (where permitted), and account closure support.</p>
            <ul>
                <li>Privacy Contact: [Privacy Contact Email]</li>
                <li>Rights Request Process: [Data Request Process URL]</li>
            </ul>

            <h2>7. Security and User Responsibility</h2>
            <p>FrontStore applies reasonable platform safeguards. Users are responsible for protecting account credentials and reporting suspicious access quickly.</p>

            <h2>8. Children and Minors</h2>
            <p>FrontStore is not intended for minors below the applicable legal age in [Jurisdiction] without legally valid guardian involvement where required.</p>

            <h2>9. Policy Updates</h2>
            <p>This Privacy Policy may be updated as business operations, platform features, or legal requirements evolve.</p>

            <h2>10. Contact</h2>
            <ul>
                <li>Business Name: FrontStore</li>
                <li>Registered Address: Surat, Gujarat.</li>
                <li>Privacy Contact Email: frontstore.team@outlook.com</li>
            </ul>

            <div class="footer-line">Last Updated: March 27, 2026</div>
        </div>
    </div>

    @include('shop.partials.footer')
</body>
</html>