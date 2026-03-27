<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }

        /* Page Header */
        .page-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 50%, #485769 100%);
            border-radius: 16px;
            padding: 35px 40px;
            margin-bottom: 25px;
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
        .page-header p {
            font-size: 14px;
            opacity: 0.85;
            position: relative;
        }

        /* Search Box */
        .search-box {
            background: white;
            border-radius: 12px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .search-box h3 {
            font-size: 18px;
            color: #0f1111;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .search-box h3 i { color: #ff9900; }
        .search-input-wrap {
            display: flex;
            gap: 10px;
        }
        .search-input-wrap input {
            flex: 1;
            padding: 14px 18px;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
        }
        .search-input-wrap input:focus {
            outline: none;
            border-color: #ff9900;
            box-shadow: 0 0 0 3px rgba(255,153,0,0.1);
        }
        .search-input-wrap button {
            background: linear-gradient(135deg, #ff9900, #febd69);
            border: none;
            padding: 14px 30px;
            border-radius: 10px;
            color: #0f1111;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s;
        }
        .search-input-wrap button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(255,153,0,0.3);
        }

        /* Stats Bar / Quick Links */
        .stats-bar {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 15px;
            margin-bottom: 25px;
        }
        .stat-card {
            background: white;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 12px;
            transition: all 0.3s;
            text-decoration: none;
            cursor: pointer;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
        }
        .stat-icon.orange { background: linear-gradient(135deg, #ff9900, #febd69); color: white; }
        .stat-icon.green { background: linear-gradient(135deg, #007600, #00a32a); color: white; }
        .stat-icon.blue { background: linear-gradient(135deg, #232f3e, #37475a); color: white; }
        .stat-icon.purple { background: linear-gradient(135deg, #6b46c1, #9f7aea); color: white; }
        .stat-icon.red { background: linear-gradient(135deg, #c41e3a, #e53e3e); color: white; }
        .stat-icon.teal { background: linear-gradient(135deg, #0d9488, #14b8a6); color: white; }
        .stat-content { text-align: center; }
        .stat-content h3 { font-size: 14px; font-weight: 600; color: #0f1111; }
        .stat-content p { font-size: 12px; color: #565959; margin-top: 3px; }

        /* Section Title */
        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 20px;
        }
        .section-header h2 {
            font-size: 20px;
            font-weight: 700;
            color: #0f1111;
        }
        .section-header .icon-box {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #232f3e, #37475a);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #febd69;
            font-size: 16px;
        }

        /* Help Grid */
        .help-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        .help-card {
            background: white;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .help-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .help-card-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            padding: 18px 22px;
            color: white;
            font-weight: 600;
            font-size: 16px;
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .help-card-header i { color: #febd69; font-size: 18px; }
        .help-card-body { padding: 8px 0; }
        .help-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 14px 22px;
            color: #0f1111;
            text-decoration: none;
            font-size: 14px;
            transition: all 0.2s;
            border-left: 3px solid transparent;
        }
        .help-link:hover {
            background: linear-gradient(90deg, rgba(255,153,0,0.08) 0%, transparent 100%);
            border-left-color: #ff9900;
            color: #ff9900;
        }
        .help-link i {
            color: #888;
            font-size: 12px;
            transition: all 0.2s;
        }
        .help-link:hover i { color: #ff9900; transform: translateX(3px); }

        /* Contact Section */
        .contact-section {
            background: white;
            border-radius: 14px;
            padding: 35px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            text-align: center;
            position: relative;
            overflow: hidden;
        }
        .contact-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #ff9900, #febd69, #ff9900);
        }
        .contact-section h3 {
            font-size: 22px;
            color: #0f1111;
            margin-bottom: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        .contact-section h3 i { color: #ff9900; }
        .contact-section p {
            color: #565959;
            margin-bottom: 25px;
            font-size: 15px;
        }
        .contact-options {
            display: flex;
            justify-content: center;
            gap: 15px;
            flex-wrap: wrap;
        }
        .contact-btn {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 14px 28px;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s;
        }
        .contact-btn.primary {
            background: linear-gradient(135deg, #febd69 0%, #f5c842 100%);
            color: #0f1111;
            box-shadow: 0 4px 15px rgba(254,189,105,0.3);
        }
        .contact-btn.primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(254,189,105,0.4);
        }
        .contact-btn.secondary {
            background: linear-gradient(135deg, #232f3e, #37475a);
            color: white;
        }
        .contact-btn.secondary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(35,47,62,0.3);
        }
        .contact-btn.outline {
            background: white;
            color: #232f3e;
            border: 2px solid #232f3e;
        }
        .contact-btn.outline:hover {
            background: #232f3e;
            color: white;
        }

        /* FAQ Accordion */
        .faq-section {
            background: white;
            border-radius: 14px;
            padding: 25px 30px;
            margin-bottom: 25px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .faq-item {
            border-bottom: 1px solid #eee;
        }
        .faq-item:last-child { border-bottom: none; }
        .faq-question {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 0;
            cursor: pointer;
            font-weight: 600;
            color: #0f1111;
            transition: color 0.2s;
        }
        .faq-question:hover { color: #ff9900; }
        .faq-question i { color: #888; transition: transform 0.3s; }
        .faq-question.active i { transform: rotate(180deg); color: #ff9900; }
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            color: #565959;
            font-size: 14px;
            line-height: 1.7;
        }
        .faq-answer.active {
            max-height: 200px;
            padding-bottom: 18px;
        }

        /* Footer */
        .footer {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            border-radius: 14px;
            padding: 25px;
            text-align: center;
            color: rgba(255,255,255,0.8);
            margin-top: 30px;
        }
        .footer a {
            color: #febd69;
            text-decoration: none;
            transition: color 0.2s;
        }
        .footer a:hover { color: #ff9900; }

        @media (max-width: 768px) {
            .container { padding: 15px; }
            .page-header { padding: 25px 20px; }
            .page-header h1 { font-size: 22px; }
            .stats-bar { grid-template-columns: repeat(2, 1fr); }
            .help-grid { grid-template-columns: 1fr; }
            .contact-options { flex-direction: column; }
            .contact-btn { width: 100%; justify-content: center; }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">

        <!-- Page Header -->
        <div class="page-header">
            <div class="breadcrumb">
                <a href="{{ route('shop.index') }}"><i class="fas fa-home"></i> Home</a>
                <span>/</span>
                <span style="color: #febd69;">Help Center</span>
            </div>
            <h1><i class="fas fa-headset"></i> How can we help you?</h1>
            <p>Find answers, troubleshoot issues, or contact our support team</p>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <h3><i class="fas fa-search"></i> Search for help</h3>
            <div class="search-input-wrap">
                <input type="text" placeholder="Type your question or keyword..." id="helpSearch">
                <button type="button"><i class="fas fa-search"></i> Search</button>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="stats-bar">
            <a href="{{ route('profile.orders') }}" class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-box"></i></div>
                <div class="stat-content">
                    <h3>Track Order</h3>
                    <p>Where's my package?</p>
                </div>
            </a>
            <a href="#" class="stat-card">
                <div class="stat-icon purple"><i class="fas fa-credit-card"></i></div>
                <div class="stat-content">
                    <h3>Payments</h3>
                    <p>Payment options</p>
                </div>
            </a>
            <a href="{{ route('profile.index') }}" class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-user-cog"></i></div>
                <div class="stat-content">
                    <h3>Account</h3>
                    <p>Manage settings</p>
                </div>
            </a>
            <a href="#" class="stat-card">
                <div class="stat-icon red"><i class="fas fa-shield-alt"></i></div>
                <div class="stat-content">
                    <h3>Security</h3>
                    <p>Stay safe online</p>
                </div>
            </a>
            <a href="#" class="stat-card">
                <div class="stat-icon teal"><i class="fas fa-truck"></i></div>
                <div class="stat-content">
                    <h3>Shipping</h3>
                    <p>Delivery info</p>
                </div>
            </a>
        </div>

        <!-- Section Title -->
        <div class="section-header">
            <div class="icon-box"><i class="fas fa-book-open"></i></div>
            <h2>Browse Help Topics</h2>
        </div>

        <!-- Help Grid -->
        <div class="help-grid">
            <!-- Orders & Shipping -->
            <div class="help-card">
                <div class="help-card-header">
                    <i class="fas fa-shipping-fast"></i> Orders & Shipping
                </div>
                <div class="help-card-body">
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Where is my order?</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Track my package</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Delivery options and speeds</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Change or cancel an order</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Order not received</a>
                </div>
            </div>

            <!-- Payments -->
            <div class="help-card">
                <div class="help-card-header">
                    <i class="fas fa-credit-card"></i> Payments
                </div>
                <div class="help-card-body">
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Payment methods</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Payment declined</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Promo codes & offers</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Cash on Delivery (COD)</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> UPI & wallet payments</a>
                </div>
            </div>

            <!-- Account & Security -->
            <div class="help-card">
                <div class="help-card-header">
                    <i class="fas fa-user-shield"></i> Account & Security
                </div>
                <div class="help-card-body">
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Change password</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Update email or phone</a>
                    <a href="{{ route('profile.addresses') }}" class="help-link"><i class="fas fa-chevron-right"></i> Manage addresses</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Two-factor authentication</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Delete my account</a>
                </div>
            </div>

            <!-- Shipping & Delivery -->
            <div class="help-card">
                <div class="help-card-header">
                    <i class="fas fa-truck"></i> Shipping & Delivery
                </div>
                <div class="help-card-body">
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Shipping rates</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Estimated delivery times</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> International shipping</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Delivery partners</a>
                    <a href="#" class="help-link"><i class="fas fa-chevron-right"></i> Missed delivery</a>
                </div>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="section-header">
            <div class="icon-box"><i class="fas fa-question-circle"></i></div>
            <h2>Frequently Asked Questions</h2>
        </div>

        <div class="faq-section">
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I track my order?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    You can track your order by going to "My Orders" in your account. Click on the order you want to track, and you'll see the current status and estimated delivery date. You'll also receive email updates as your order progresses.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    What payment methods do you accept?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    We accept all major credit/debit cards (Visa, Mastercard, RuPay), UPI payments (Google Pay, PhonePe, Paytm), Net Banking, and Cash on Delivery (COD) for eligible orders.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I change my delivery address?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    If your order hasn't been shipped yet, you can change the delivery address from "My Orders". Once shipped, the address cannot be changed. You can also manage your saved addresses in "My Addresses" section of your profile.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How long does delivery take?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Standard delivery takes 3-5 business days for metro cities and 5-7 business days for other areas. Express delivery is available for select pincodes with 1-2 day delivery. You'll see the estimated delivery date at checkout.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    How do I update my account information?
                    <i class="fas fa-chevron-down"></i>
                </div>
                <div class="faq-answer">
                    Go to "My Account" from the navigation menu, then click on "Profile Settings". From there you can update your name, email, phone number, and password. Changes are saved immediately.
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h3><i class="fas fa-comments"></i> Still need help?</h3>
            <p>Our customer support team is available 24/7 to assist you</p>
            <div class="contact-options">
                <a href="#" class="contact-btn primary">
                    <i class="fas fa-comment-dots"></i> Live Chat
                </a>
                <a href="mailto:frontstore.team@outlook.com" class="contact-btn secondary">
                    <i class="fas fa-envelope"></i> Email Support
                </a>
                <a href="tel:1800-123-4567" class="contact-btn outline">
                    <i class="fas fa-phone"></i> 1800-123-4567
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>&copy; 2026 FrontStore. All rights reserved. | <a href="{{ route('shop.index') }}">Back to Shopping</a> | <a href="{{ route('profile.index') }}">My Account</a></p>
        </div>

    </div>

    <script>
        function toggleFaq(element) {
            const answer = element.nextElementSibling;
            const isActive = element.classList.contains('active');
            
            // Close all FAQs
            document.querySelectorAll('.faq-question').forEach(q => q.classList.remove('active'));
            document.querySelectorAll('.faq-answer').forEach(a => a.classList.remove('active'));
            
            // Toggle current
            if (!isActive) {
                element.classList.add('active');
                answer.classList.add('active');
            }
        }

        // Search functionality
        document.getElementById('helpSearch').addEventListener('keyup', function(e) {
            if (e.key === 'Enter') {
                const query = this.value.toLowerCase();
                if (query) {
                    // Highlight matching links
                    document.querySelectorAll('.help-link').forEach(link => {
                        if (link.textContent.toLowerCase().includes(query)) {
                            link.style.background = 'rgba(255,153,0,0.15)';
                            link.style.borderLeftColor = '#ff9900';
                        } else {
                            link.style.background = '';
                            link.style.borderLeftColor = 'transparent';
                        }
                    });
                }
            }
        });
    </script>

</body>
</html>
