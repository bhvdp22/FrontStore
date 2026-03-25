<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seller Central - About Us</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Poppins", sans-serif;
        }

        /* Loading Spinner */
        #loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            transition: opacity 0.5s ease-out;
        }

        #loader.hide {
            opacity: 0;
            pointer-events: none;
        }

        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #f3f3f3;
            border-top: 6px solid #002e36;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        body {
            background-color: #f1f1f1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Navigation */
        .navbar {
            background-color: #002e36;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 15px;
            color: white;
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-left {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logo {
            font-family: 'Dancing Script', cursive;
            font-size: 24px;
            font-weight: bold;
            letter-spacing: -0.5px;
        }

        .nav-right {
            display: flex;
            align-items: center;
            gap: 20px;
            font-size: 14px;
        }

        .nav-right a {
            color: white;
            text-decoration: none;
            cursor: pointer;
        }

        .nav-right a:hover {
            text-decoration: underline;
        }

        /* Main Content */
        .main-content {
            padding: 40px 30px;
            flex: 1;
            max-width: 1400px;
            margin: 0 auto;
            width: 100%;
        }

        .page-header {
            background-color: white;
            padding: 30px;
            border-radius: 4px;
            margin-bottom: 30px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .page-header h1 {
            color: #002e36;
            font-size: 32px;
            margin-bottom: 10px;
        }

        .page-header p {
            color: #555;
            font-size: 16px;
            line-height: 1.6;
        }

        /* Section Styles */
        .section {
            background-color: white;
            padding: 30px;
            margin-bottom: 20px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .section h2 {
            color: #002e36;
            font-size: 24px;
            margin-bottom: 20px;
            border-bottom: 2px solid #ff9900;
            padding-bottom: 10px;
        }

        .section h3 {
            color: #146eb4;
            font-size: 18px;
            margin-top: 20px;
            margin-bottom: 15px;
        }

        .section p {
            color: #555;
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 15px;
            text-align: justify;
        }

        /* Features Grid */
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .feature-card {
            background: linear-gradient(135deg, #002e36 0%, #004d5c 100%);
            color: white;
            padding: 25px;
            border-radius: 4px;
            border-left: 4px solid #ff9900;
            transition: all 0.3s ease;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.15);
        }

        .feature-card i {
            font-size: 28px;
            color: #ff9900;
            margin-bottom: 15px;
            display: block;
        }

        .feature-card h3 {
            color: white;
            margin-top: 0;
            font-size: 16px;
            margin-bottom: 10px;
        }

        .feature-card p {
            color: #e0e0e0;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
            text-align: left;
        }

        /* Statistics */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }

        .stat-box {
            background-color: #f1f1f1;
            padding: 20px;
            border-radius: 4px;
            text-align: center;
            border-top: 3px solid #ff9900;
        }

        .stat-number {
            font-size: 32px;
            font-weight: bold;
            color: #ff9900;
            margin-bottom: 10px;
        }

        .stat-label {
            color: #555;
            font-size: 14px;
        }

        /* Highlight Box */
        .highlight-box {
            background-color: #fef4e6;
            border-left: 4px solid #ff9900;
            padding: 20px;
            margin: 20px 0;
            border-radius: 2px;
        }

        .highlight-box p {
            color: #333;
            margin: 0;
        }

        /* Footer */
        footer {
            background-color: #002e36;
            color: white;
            margin-top: 40px;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            padding: 40px 30px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
        }

        .footer-section h3 {
            color: #ff9900;
            margin-bottom: 15px;
            font-size: 16px;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 10px;
        }

        .footer-section a {
            color: #e0e0e0;
            text-decoration: none;
            font-size: 14px;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #ff9900;
        }

        .footer-bottom {
            border-top: 1px solid #004d5c;
            padding: 20px 30px;
            text-align: center;
            color: #999;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .main-content {
                padding: 20px 15px;
            }

            .page-header {
                padding: 20px;
            }

            .page-header h1 {
                font-size: 24px;
            }

            .section {
                padding: 20px;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="nav-left">
            <div class="logo">Seller Central</div>
        </div>
        <div class="nav-right">
            <a href="/">Dashboard</a>
            <a href="/shop">Shop</a>
            <a href="/about">About</a>
            <a href="/help">Help</a>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1>About Seller Central Platform</h1>
            <p>Your Complete Solution for Selling Online - Manage Products, Orders, Payments & Campaigns All in One Place</p>
        </div>

        <!-- About Section -->
        <div class="section">
            <h2>Welcome to Seller Central</h2>
            <p>
                Seller Central is a comprehensive, enterprise-grade platform built with Laravel that empowers sellers to manage their entire e-commerce business efficiently. Whether you're a small vendor or a large enterprise, our platform provides all the tools you need to succeed in the digital marketplace.
            </p>
            <p>
                With a focus on simplicity and power, Seller Central combines an intuitive user interface with robust backend infrastructure to deliver an unparalleled selling experience. Our dashboard gives you complete visibility and control over every aspect of your business.
            </p>
        </div>

        <!-- Features Section -->
        <div class="section">
            <h2>Platform Features</h2>
            <p>Explore the comprehensive tools available to streamline your business operations:</p>
            
            <div class="features-grid">
                <div class="feature-card">
                    <i class="fas fa-cube"></i>
                    <h3>Product Management</h3>
                    <p>Create, edit, and manage your product catalog with detailed descriptions, pricing, and inventory tracking. Add multiple images and organize products by categories.</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-shopping-cart"></i>
                    <h3>Shopping Cart</h3>
                    <p>Provide customers with a seamless shopping experience featuring an intuitive cart system with easy checkout and multiple payment options.</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-credit-card"></i>
                    <h3>Payment Processing</h3>
                    <p>Integrated Razorpay payment gateway enabling secure, reliable transactions. Support multiple payment methods including cards, wallets, and UPI.</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-box"></i>
                    <h3>Order Management</h3>
                    <p>Complete order tracking and fulfillment system. Monitor order status, manage customer details, and streamline your order processing workflow.</p>
                </div>

                <div class="feature-card">
                    <i class="fas fa-bullhorn"></i>
                    <h3>Marketing Campaigns</h3>
                    <p>Create and manage targeted marketing campaigns. Feature sponsored products and run promotional campaigns to boost sales and customer engagement.</p>
                </div>

                <!-- <div class="feature-card">
                    <i class="fas fa-users"></i>
                    <h3>User Management</h3>
                    <p>Comprehensive user authentication and profile management. Create customer accounts, manage preferences, and maintain detailed customer relationships.</p>
                </div> -->

                <!-- <div class="feature-card">
                    <i class="fas fa-newspaper"></i>
                    <h3>Content Management</h3>
                    <p>Create and publish blog posts and articles to engage your audience. Build thought leadership and drive organic traffic to your store.</p>
                </div> -->

                <div class="feature-card">
                    <i class="fas fa-chart-bar"></i>
                    <h3>Analytics & Reports</h3>
                    <p>Access detailed insights into your business performance. Monitor sales trends, customer behavior, and operational metrics with comprehensive reporting tools.</p>
                </div>

                <!-- <div class="feature-card">
                    <i class="fas fa-ads"></i>
                    <h3>Advertising & Sponsorship</h3>
                    <p>Promote selected products with sponsored listings. Increase visibility and reach more customers through our advertising platform.</p>
                </div> -->
            </div>
        </div>

        <!-- Why Choose Us -->
        <div class="section">
            <h2>Why Choose Seller Central?</h2>
            
            <h3><i class="fas fa-shield-alt"></i> Built on Proven Technology</h3>
            <p>
                Developed with Laravel, one of the most trusted and widely-adopted PHP frameworks. Our platform benefits from excellent community support, regular updates, and enterprise-level security standards.
            </p>

            <h3><i class="fas fa-lock"></i> Security & Compliance</h3>
            <p>
                Your business data and customer information are protected with industry-standard encryption and security protocols. We comply with international data protection regulations including GDPR standards.
            </p>

            <h3><i class="fas fa-expand"></i> Scalability</h3>
            <p>
                Start small and grow without limits. Our architecture scales seamlessly from a single vendor to thousands of concurrent users, handling increasing traffic and transaction volumes effortlessly.
            </p>

            <h3><i class="fas fa-cog"></i> Easy Integration</h3>
            <p>
                Integrate with popular payment gateways, shipping providers, and third-party services. Our REST API allows custom integrations and extensions to meet your specific business needs.
            </p>

            <h3><i class="fas fa-headset"></i> Dedicated Support</h3>
            <p>
                Access comprehensive help resources, documentation, and support to maximize your success. Our team is committed to helping you achieve your business goals.
            </p>

            <h3><i class="fas fa-mobile-alt"></i> Responsive Design</h3>
            <p>
                Access your dashboard from any device. Our mobile-responsive interface ensures you can manage your business on the go with full functionality on smartphones and tablets.
            </p>
        </div>

        <!-- Platform Capabilities -->
        <div class="section">
            <h2>Our Capabilities</h2>
            
            <div class="stats-grid">
                <div class="stat-box">
                    <div class="stat-number">∞</div>
                    <div class="stat-label">Products You Can List</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">24/7</div>
                    <div class="stat-label">Platform Availability</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">100%</div>
                    <div class="stat-label">Secure Transactions</div>
                </div>
                <div class="stat-box">
                    <div class="stat-number">Instant</div>
                    <div class="stat-label">Real-time Analytics</div>
                </div>
            </div>

            <div class="highlight-box">
                <p>
                    <strong>Pro Tip:</strong> Leverage our comprehensive marketing tools and analytics dashboard to identify trends, optimize pricing, and maximize your revenue potential.
                </p>
            </div>
        </div>

        <!-- Getting Started -->
        <div class="section">
            <h2>Getting Started</h2>
            <p>
                Ready to launch your e-commerce business? Getting started with Seller Central is simple:
            </p>
            <ol style="color: #555; font-size: 15px; line-height: 1.8; margin-left: 20px;">
                <li style="margin-bottom: 10px;"><strong>Create Your Account</strong> - Register and set up your seller profile</li>
                <li style="margin-bottom: 10px;"><strong>Add Your Products</strong> - Upload your product catalog with descriptions and pricing</li>
                <li style="margin-bottom: 10px;"><strong>Configure Payments</strong> - Set up payment processing through Razorpay</li>
                <li style="margin-bottom: 10px;"><strong>Launch Your Store</strong> - Go live and start selling immediately</li>
                <li style="margin-bottom: 10px;"><strong>Monitor & Optimize</strong> - Use analytics and reports to grow your business</li>
            </ol>

            <div class="highlight-box" style="margin-top: 20px;">
                <p>
                    <strong>Start Today:</strong> Join hundreds of successful sellers already using Seller Central to grow their businesses. Sign up now and get exclusive welcome bonuses for new sellers!
                </p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>Platform</h3>
                <ul>
                    <li><a href="/">Dashboard</a></li>
                    <li><a href="/products">Manage Products</a></li>
                    <li><a href="/orders">View Orders</a></li>
                    <li><a href="/payments">Payments</a></li>
                    <li><a href="/reports">Reports</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Sellers</h3>
                <ul>
                    <li><a href="/help">Seller Help</a></li>
                    <li><a href="/">Getting Started</a></li>
                    <li><a href="/">Best Practices</a></li>
                    <li><a href="/">Community Forum</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Company</h3>
                <ul>
                    <li><a href="/">About Us</a></li>
                    <li><a href="/">Contact Support</a></li>
                    <li><a href="/">Privacy Policy</a></li>
                    <li><a href="/">Terms of Service</a></li>
                </ul>
            </div>

            <div class="footer-section">
                <h3>Connect</h3>
                <ul>
                    <li><a href="/"><i class="fab fa-facebook"></i> Facebook</a></li>
                    <li><a href="/"><i class="fab fa-twitter"></i> Twitter</a></li>
                    <li><a href="/"><i class="fab fa-linkedin"></i> LinkedIn</a></li>
                    <li><a href="/"><i class="fab fa-instagram"></i> Instagram</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; 2026 Seller Central Platform. All rights reserved. | Powered by Laravel | <a href="/" style="color: #ff9900; text-decoration: none;">Privacy</a> | <a href="/" style="color: #ff9900; text-decoration: none;">Terms</a></p>
        </div>
    </footer>

    <script>
        // Hide loader after 1.5 seconds
        window.addEventListener('load', function() {
            setTimeout(function() {
                const loader = document.getElementById('loader');
                loader.classList.add('hide');
                setTimeout(function() {
                    loader.style.display = 'none';
                }, 500);
            }, 1500);
        });
    </script>
</body>
</html>