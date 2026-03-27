<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Disclaimer - FrontStore</title>
    <meta name="description" content="FrontStore marketplace disclaimer about seller content, third-party services, pricing changes, and service availability.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&family=Montserrat:wght@600;700;800&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f6f7f9 0%, #eceff3 100%); color: #111827; min-height: 100vh; }
        .container { max-width: 980px; margin: 0 auto; padding: 28px 18px; }
        .header-card { background: linear-gradient(135deg, #2b2f36 0%, #434a56 100%); color: #fff; border-radius: 14px; padding: 30px 28px; margin-bottom: 24px; }
        .header-card h1 { font-size: 30px; display: flex; gap: 12px; align-items: center; margin-bottom: 8px; }
        .header-card h1 i { color: #fbbf24; }
        .header-card p { font-size: 14px; color: #e9eef8 !important; opacity: 1; text-shadow: 0 1px 1px rgba(0,0,0,0.22); }
        .policy-card { background: #fff; border-radius: 14px; box-shadow: 0 6px 22px rgba(0,0,0,0.08); padding: 28px; }
        h2 { font-family: 'Montserrat', sans-serif; font-size: 20px; color: #1f2937; margin: 22px 0 10px; border-bottom: 2px solid #fbbf24; display: inline-block; padding-bottom: 6px; }
        h2:first-of-type { margin-top: 0; }
        p { font-size: 14.5px; line-height: 1.8; margin-bottom: 10px; color: #374151; }
        ul { padding-left: 22px; margin-bottom: 12px; }
        li { font-size: 14.5px; line-height: 1.8; color: #374151; margin-bottom: 5px; }
        .note { background: #fffbeb; border-left: 4px solid #f59e0b; padding: 14px; border-radius: 8px; margin: 12px 0 18px; font-size: 14px; }
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
            <h1><i class="fas fa-scale-balanced"></i> Disclaimer</h1>
            <p>Important marketplace usage terms covering seller-listed content, external services, and platform limitations.</p>
        </div>

        <div class="policy-card">
            <div class="note">
                FrontStore is a marketplace platform connecting customers and independent sellers. Product information, inventory, and post-order handling involve multiple parties.
            </div>

            <h2>1. General Information</h2>
            <p>All information on FrontStore is provided for general marketplace use. We aim for accuracy and operational clarity, but listing content, availability, and timelines can change.</p>

            <h2>2. Seller-Generated Product Information</h2>
            <ul>
                <li>Product descriptions, specifications, images, and listing claims are primarily provided by sellers.</li>
                <li>FrontStore may moderate listings, but cannot guarantee every seller statement is complete or error-free at all times.</li>
                <li>Customers should review listing details and seller information before purchase.</li>
            </ul>

            <h2>3. Pricing and Availability</h2>
            <p>Prices, stock status, offers, and delivery estimates are subject to change due to seller updates, inventory movement, and operational constraints.</p>

            <h2>4. Reviews and User Content</h2>
            <p>Ratings and reviews represent user opinions. FrontStore performs moderation, but does not guarantee that user opinions or seller responses are exhaustive or universally applicable.</p>

            <h2>5. Third-Party Services</h2>
            <ul>
                <li>Payments rely on third-party processors (for example [Payment Gateway Name]).</li>
                <li>Shipping/pickup may depend on logistics partners where enabled.</li>
                <li>Email, storage, and technical infrastructure may include external service providers.</li>
            </ul>
            <p>FrontStore is not responsible for external service interruptions outside reasonable platform control.</p>

            <h2>6. Returns, Refunds, and Settlements</h2>
            <p>Return and refund outcomes depend on policy compliance, evidence, seller review, inspection workflow, and admin processing checks where required.</p>

            <h2>7. Availability and Service Continuity</h2>
            <p>FrontStore does not guarantee uninterrupted or error-free operation at all times. Maintenance windows, technical events, security controls, and third-party outages can impact availability.</p>

            <h2>8. External Links</h2>
            <p>Any external links or embedded third-party resources are provided for convenience. Their content and policies are outside FrontStore control.</p>

            <h2>9. Limitation of Liability</h2>
            <p>To the extent allowed by law, FrontStore is not liable for indirect or consequential losses arising from seller-provided content, external provider downtime, delayed bank/gateway settlements, or user misuse.</p>
            <p>This clause does not remove rights that cannot be legally limited under applicable consumer law.</p>

            <h2>10. Jurisdiction</h2>
            <p>This Disclaimer is governed by the laws of [Jurisdiction], subject to mandatory legal protections and competent forum requirements.</p>

            <h2>11. Contact</h2>
            <ul>
                <li>Business Name: FrontStore</li>
                <li>Support Email: frontstore.team@outlook.com</li>
                <li>Registered Address: Surat, Gujarat.</li>
            </ul>

            <div class="footer-line">Last Updated: March 27, 2026</div>
        </div>
    </div>

    @include('shop.partials.footer')
</body>
</html>