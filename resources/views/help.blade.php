<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Help Center</title>
    <style>
        /* Loading Spinner */
        #loader { position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: #fff; display: flex; justify-content: center; align-items: center; z-index: 9999; transition: opacity 0.5s ease-out; }
        #loader.hide { opacity: 0; pointer-events: none; }
        .spinner { width: 60px; height: 60px; border: 6px solid #f3f3f3; border-top: 6px solid #002e36; border-radius: 50%; animation: spin 1s linear infinite; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }

        body { font-family: sans-serif; margin: 0; background-color: #f4f6f8; }
        
        /* Matching the Navbar from your screenshot */
        .navbar {
            background-color: #002f34; /* Dark Teal */
            color: white;
            padding: 15px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar a { color: white; text-decoration: none; margin-left: 20px; font-weight: bold; }
        .navbar a:hover { color: #ccc; }

        /* Page Content */
        .container { max-width: 1000px; margin: 40px auto; padding: 0 20px; }
        h1 { color: #333; }
        
        /* Help Grid */
        .help-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }
        
        .help-card {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.2s;
        }
        .help-card:hover { transform: translateY(-5px); }
        .help-card h3 { color: #002f34; margin-top: 0; }
        .help-card p { color: #666; line-height: 1.5; }
        .btn-link { color: #007bff; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <!-- Loading Spinner -->
    <div id="loader">
        <div class="spinner"></div>
    </div>

    <div class="navbar">
        <div class="brand">Seller Central Help Center</div>
        <div class="menu">
            <a href="#">EN ▼</a>
            <a href="#">✉</a> <a href="#">⚙</a> <a href="{{ route('help') }}">Help</a>
            <a href="/">Dashboard</a>
            <a href="/logout">Logout</a>
        </div>
    </div>

    <div class="container">
        <h1>How can we help you?</h1>
        <p>Browse our guides or contact support below.</p>

        <div class="help-grid">
            
            <div class="help-card">
                <h3>📦 Managing Products</h3>
                <p>Learn how to add, edit, and delete products in your inventory correctly without errors.</p>
                <a href="#" class="btn-link">Read Guide &rarr;</a>
            </div>

            <div class="help-card">
                <h3>👤 Account & Security</h3>
                <p>Update your profile, change your password, and manage your notification preferences.</p>
                <a href="#" class="btn-link">Manage Account &rarr;</a>
            </div>

            <div class="help-card">
                <h3>🛠 Technical Support</h3>
                <p>Facing "Internal Server Errors" or "Missing Parameters"? Check our troubleshooting logs.</p>
                <a href="#" class="btn-link">View Logs &rarr;</a>
            </div>

            <div class="help-card">
                <h3>📞 Contact Us</h3>
                <p>Still stuck? Reach out to our support team directly.</p>
                <p><strong>Email:</strong> {{ $supportEmail }}</p>
            </div>

        </div>
    </div>

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