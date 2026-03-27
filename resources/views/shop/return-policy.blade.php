<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Return Policy — FrontStore Multi-Vendor Marketplace</title>
    <meta name="description" content="Learn how to return products purchased on FrontStore. Our return policy covers eligibility, return window, step-by-step process, and what to expect from our multi-vendor marketplace.">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; color: #0f1111; }
        .container { max-width: 900px; margin: 0 auto; padding: 30px 20px; }

        .page-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 50%, #485769 100%);
            border-radius: 16px;
            padding: 35px 40px;
            margin-bottom: 30px;
            color: white;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,189,105,0.15) 0%, transparent 70%);
            border-radius: 50%;
        }
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 13px;
            margin-bottom: 15px;
            position: relative;
        }
        .breadcrumb a { color: rgba(255,255,255,0.7); text-decoration: none; }
        .breadcrumb a:hover { color: #febd69; }
        .breadcrumb span { color: rgba(255,255,255,0.5); }
        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        .page-header h1 i { color: #febd69; }
        .page-header .subtitle {
            font-size: 14px;
            opacity: 0.85;
            position: relative;
        }

        .policy-card {
            background: white;
            border-radius: 14px;
            padding: 35px 40px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            line-height: 1.8;
        }
        .policy-card h2 {
            font-size: 20px;
            font-weight: 700;
            color: #232f3e;
            margin: 30px 0 14px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #febd69;
            display: inline-block;
        }
        .policy-card h2:first-child { margin-top: 0; }
        .policy-card h3 {
            font-size: 16px;
            font-weight: 600;
            color: #37475a;
            margin: 20px 0 10px 0;
        }
        .policy-card p {
            font-size: 14.5px;
            color: #333;
            margin-bottom: 12px;
        }
        .policy-card ul, .policy-card ol {
            padding-left: 22px;
            margin-bottom: 14px;
        }
        .policy-card li {
            font-size: 14.5px;
            color: #333;
            margin-bottom: 6px;
            line-height: 1.7;
        }
        .policy-card li strong { color: #232f3e; }

        .policy-table {
            width: 100%;
            border-collapse: collapse;
            margin: 16px 0;
            font-size: 14px;
        }
        .policy-table th {
            background: #232f3e;
            color: white;
            padding: 12px 16px;
            text-align: left;
            font-weight: 600;
        }
        .policy-table td {
            padding: 11px 16px;
            border-bottom: 1px solid #eee;
            color: #333;
        }
        .policy-table tr:hover td { background: #fef9f0; }

        .policy-intro {
            font-size: 15px;
            color: #555;
            margin-bottom: 20px;
            padding: 18px 22px;
            background: linear-gradient(135deg, #fef9f0 0%, #fff7e6 100%);
            border-left: 4px solid #febd69;
            border-radius: 0 10px 10px 0;
        }

        .policy-footer {
            text-align: center;
            padding: 18px;
            font-size: 13px;
            color: #888;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }

        .cross-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #ff9900;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.2s;
        }
        .cross-link:hover { color: #e68a00; text-decoration: underline; }

        @media (max-width: 768px) {
            .container { padding: 15px; }
            .page-header { padding: 25px 20px; }
            .page-header h1 { font-size: 22px; }
            .policy-card { padding: 22px 18px; }
            .policy-table { font-size: 13px; }
            .policy-table th, .policy-table td { padding: 8px 10px; }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">

        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('shop.index') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <span style="color: #febd69;">Return Policy</span>
            </div>
            <h1><i class="fas fa-undo-alt"></i> Return Policy</h1>
            <p class="subtitle">Simple, fair returns — because trust is built on transparency.</p>
        </div>

        <div class="policy-card">

            <div class="policy-intro">
                At FrontStore, we believe shopping should feel safe — even after the purchase is made. Because our marketplace connects you with multiple independent sellers, we've built a return process that is clear, structured, and fair for everyone involved.
            </div>

            <h2>1. When Can You Return a Product?</h2>
            <p>You may request a return for a product purchased on FrontStore if <strong>all</strong> of the following conditions are met:</p>
            <ul>
                <li>The order has been marked as <strong>"Delivered"</strong> in your account.</li>
                <li>The return request is submitted <strong>within 30 days</strong> of the delivery date.</li>
                <li>The product falls within a returnable category (see Section 3 below).</li>
                <li>The item is in its original condition unless the return reason is damage, defect, or wrong item.</li>
            </ul>

            <h2>2. Accepted Reasons for Return</h2>
            <ul>
                <li><strong>Defective product</strong> — The item arrived with a manufacturing defect.</li>
                <li><strong>Wrong item received</strong> — The product delivered does not match what was ordered.</li>
                <li><strong>Product not as described</strong> — The item significantly differs from the listing description or images.</li>
                <li><strong>Damaged during shipping</strong> — The product was damaged in transit.</li>
                <li><strong>Size or fit issue</strong> — The size or fit does not match the listing specifications.</li>
                <li><strong>Quality not satisfactory</strong> — The product quality does not meet reasonable expectations.</li>
                <li><strong>Late delivery</strong> — The item was delivered significantly past the expected delivery window.</li>
                <li><strong>Changed my mind</strong> — You no longer wish to keep the product (subject to item condition and category eligibility).</li>
                <li><strong>Other</strong> — Any other valid reason, which must be explained in your return request.</li>
            </ul>

            <h2>3. Non-Returnable Items</h2>
            <p>The following categories of products are generally <strong>not eligible</strong> for return unless they arrive defective, damaged, or materially different from the listing:</p>
            <ul>
                <li>Perishable goods (food, flowers, and similar items)</li>
                <li>Personal hygiene and intimate products</li>
                <li>Customized or personalized items made to order</li>
                <li>Downloadable or digital products, once accessed</li>
                <li>Items explicitly marked as <strong>"Non-Returnable"</strong> or <strong>"Final Sale"</strong> on the product listing</li>
            </ul>

            <h2>4. Product Condition Requirements</h2>
            <ul>
                <li>The item must be <strong>unused and in its original condition</strong>, unless the return reason is damage, defect, or wrong item.</li>
                <li>All original packaging, tags, labels, and accessories should be intact where possible.</li>
                <li>Products returned in a significantly altered, used, or incomplete state may be rejected after inspection by the seller.</li>
            </ul>
            <p>For items returned due to <strong>damage or defect</strong>, we understand the product may not be in original condition — the evidence you provide during the return request is what matters.</p>

            <h2>5. Proof Required</h2>
            <p>When submitting a return request, you will be asked to provide:</p>
            <ul>
                <li><strong>A reason for the return</strong> (selected from the available reasons listed above).</li>
                <li><strong>Details explaining the issue</strong> (optional, but strongly recommended — up to 1,000 characters).</li>
                <li><strong>Supporting images</strong> (up to 5 photos, JPEG/PNG/JPG/GIF format, max 5 MB each).</li>
            </ul>
            <p>Providing clear, accurate evidence significantly improves the chances of a smooth return.</p>

            <h2>6. How to Request a Return</h2>
            <ol>
                <li><strong>Log in</strong> to your FrontStore customer account.</li>
                <li>Navigate to <strong>My Orders</strong> from your profile.</li>
                <li>Find the delivered order and click <strong>"Request Return"</strong> on the eligible item.</li>
                <li>Select a <strong>return reason</strong> from the available options.</li>
                <li>Add <strong>details</strong> about the issue and upload <strong>supporting photos</strong> if applicable.</li>
                <li>Confirm the <strong>quantity</strong> you wish to return and the <strong>pickup address</strong>.</li>
                <li>Submit the return request.</li>
                <li>You will receive a <strong>return number</strong> (e.g., RET20260327XXXXX) for tracking.</li>
            </ol>

            <h2>7. What Happens After You Submit</h2>
            <table class="policy-table">
                <thead>
                    <tr>
                        <th>Stage</th>
                        <th>What Happens</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td><strong>Pending Review</strong></td><td>The seller reviews your return request and evidence.</td></tr>
                    <tr><td><strong>Approved</strong></td><td>The seller accepts the return. Pickup or shipping instructions follow.</td></tr>
                    <tr><td><strong>Rejected</strong></td><td>The seller declines the return with a reason. You will be notified.</td></tr>
                    <tr><td><strong>Pickup Scheduled</strong></td><td>A pickup is arranged from your specified address.</td></tr>
                    <tr><td><strong>Picked Up</strong></td><td>The item has been collected from you.</td></tr>
                    <tr><td><strong>Received by Seller</strong></td><td>The seller confirms receipt of the returned item.</td></tr>
                    <tr><td><strong>Inspected</strong></td><td>The seller inspects the item condition.</td></tr>
                    <tr><td><strong>Refund Initiated</strong></td><td>Once inspection is passed, the refund process begins.</td></tr>
                    <tr><td><strong>Refund Completed</strong></td><td>The refund has been processed to your payment method.</td></tr>
                </tbody>
            </table>
            <p>You can track status at any time from the <strong>Returns</strong> section of your profile and communicate directly with the seller through the built-in messaging feature.</p>

            <h2>8. Seller & Customer Responsibilities</h2>
            <h3>As a Customer</h3>
            <ul>
                <li>Provide honest and accurate reasons for the return.</li>
                <li>Submit clear supporting evidence where applicable.</li>
                <li>Package the item properly for return pickup or shipping.</li>
                <li>Respond promptly to messages from the seller regarding the return.</li>
            </ul>
            <h3>As a Seller</h3>
            <ul>
                <li>Review and respond to return requests in a timely manner.</li>
                <li>Provide clear reasons for any rejections.</li>
                <li>Schedule pickups or provide return shipping instructions promptly.</li>
                <li>Inspect returned items fairly and initiate refunds without unreasonable delay.</li>
            </ul>

            <h2>9. Pickup & Return Shipping</h2>
            <ul>
                <li><strong>Pickup:</strong> When a return is approved, the seller may schedule a pickup from the address on file (typically your original shipping address). You will receive tracking details once the pickup is arranged.</li>
                <li><strong>Self-Shipping:</strong> In some cases, you may be asked to ship the item back. The seller will provide return shipping instructions. Retain the shipping receipt and tracking number for your records.</li>
            </ul>

            <h2>10. Return Cancellation</h2>
            <p>You may cancel your return request while it is in <strong>"Pending"</strong> or <strong>"Approved"</strong> status. Once a pickup has been scheduled or completed, cancellation may no longer be available.</p>

            <h2>11. Policy Misuse</h2>
            <p>FrontStore monitors return activities across the platform. If patterns suggesting misuse are identified — such as excessive returns without valid grounds, returning used or swapped products, or submitting false claims — FrontStore reserves the right to reject future return requests, restrict account privileges, or take further action.</p>

            <h2>12. Multi-Vendor Note</h2>
            <p>Since FrontStore is a multi-vendor marketplace, return handling may vary slightly by seller. Each seller reviews and processes their own return requests. FrontStore provides the infrastructure, return tracking system, and communication tools. Our admin team can intervene if a dispute arises.</p>

            <p style="margin-top: 20px;">For refund details after a return, please see our <a href="{{ route('page.refund-policy') }}" class="cross-link"><i class="fas fa-arrow-right"></i> Refund Policy</a>.</p>

            <div class="policy-footer">
                Last Updated: March 27, 2026 &nbsp;|&nbsp; Questions? Contact us at frontstore.team@outlook.com
            </div>
        </div>

    </div>

    @include('shop.partials.footer')

</body>
</html>
