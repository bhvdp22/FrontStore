<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seller Central Login</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    
    body {
        font-family: 'Poppins', sans-serif;
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%);
        min-height: 100vh;
        color: #111;
    }

    /* Header */
    .header {
        background: linear-gradient(90deg, #232f3e 0%, #37475a 100%);
        padding: 15px 30px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .logo {
        font-family: 'Dancing Script', cursive;
        font-size: 26px;
        font-weight: bold;
        color: #fff;
    }
    .logo span { color: #ff9900; }

    .header-links a {
        color: #fff;
        text-decoration: none;
        font-size: 13px;
        margin-left: 20px;
    }
    .header-links a:hover { text-decoration: underline; }

    /* Main Container */
    .main-container {
        max-width: 900px;
        margin: 50px auto;
        padding: 0 20px;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        gap: 50px;
    }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
        width: 420px;
    }

    .form-header {
        background: linear-gradient(135deg, #232f3e, #37475a);
        color: #fff;
        padding: 25px 30px;
    }

    .form-header h2 {
        font-size: 22px;
        font-weight: 600;
        margin-bottom: 5px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .form-header p {
        font-size: 13px;
        opacity: 0.8;
    }

    .form-body {
        padding: 30px;
    }

    /* Form Groups */
    .form-group {
        margin-bottom: 20px;
    }

    .form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #333;
        margin-bottom: 8px;
    }

    .form-group label .required {
        color: #c40000;
    }

    .form-group .input-icon {
        position: relative;
    }

    .form-group .input-icon i {
        position: absolute;
        left: 15px;
        top: 50%;
        transform: translateY(-50%);
        color: #888;
    }

    .form-group .input-icon input {
        padding-left: 45px;
    }

    .form-group input {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #d5d9d9;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s;
        background: #fff;
    }

    .form-group input:focus {
        border-color: #ff9900;
        box-shadow: 0 0 0 3px rgba(255, 153, 0, 0.15);
        outline: none;
    }

    .form-group input.input-error {
        border-color: #c40000;
        box-shadow: 0 0 0 3px rgba(196, 0, 0, 0.1);
    }

    /* Error Alert */
    .error-alert {
        background: #fff5f5;
        border: 1px solid #feb2b2;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .error-alert i {
        color: #c40000;
        font-size: 18px;
    }

    .error-alert-content h4 {
        color: #c40000;
        font-size: 14px;
        margin-bottom: 5px;
    }

    .error-alert-content p {
        color: #c40000;
        font-size: 13px;
    }

    /* Buttons */
    .btn {
        padding: 12px 30px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        border: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff9900, #ffad33);
        color: #111;
        box-shadow: 0 4px 15px rgba(255, 153, 0, 0.3);
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #e88b00, #ff9900);
        transform: translateY(-1px);
        box-shadow: 0 6px 20px rgba(255, 153, 0, 0.4);
    }

    .btn-secondary {
        background: #f5f5f5;
        color: #333;
        border: 1px solid #d5d9d9;
        margin-top: 15px;
    }

    .btn-secondary:hover {
        background: #e8e8e8;
    }

    /* Legal Text */
    .legal-text {
        font-size: 12px;
        line-height: 1.6;
        color: #666;
        margin-top: 20px;
        text-align: center;
    }

    .legal-text a {
        color: #0066c0;
        text-decoration: none;
    }

    .legal-text a:hover {
        text-decoration: underline;
    }

    /* Help Link */
    .help-link {
        text-align: center;
        margin-top: 15px;
    }

    .help-link a {
        color: #0066c0;
        text-decoration: none;
        font-size: 13px;
    }

    .help-link a:hover {
        text-decoration: underline;
    }

    /* Divider */
    .divider {
        display: flex;
        align-items: center;
        text-align: center;
        color: #767676;
        font-size: 13px;
        margin: 20px 0;
    }
    .divider::before, .divider::after {
        content: '';
        flex: 1;
        border-bottom: 1px solid #e0e0e0;
    }
    .divider::before { margin-right: 15px; }
    .divider::after { margin-left: 15px; }

    /* Form Footer */
    .form-footer {
        text-align: center;
        padding: 20px 30px;
        background: #f9f9f9;
        border-top: 1px solid #e0e0e0;
    }

    .form-footer p {
        font-size: 13px;
        color: #666;
    }

    .form-footer a {
        color: #0066c0;
        text-decoration: none;
        font-weight: 600;
    }

    .form-footer a:hover {
        text-decoration: underline;
    }

    /* 3D Icon */
    .icon-3d {
        width: 350px;
        height: 350px;
        object-fit: contain;
        animation: float 3s ease-in-out infinite;
    }

    .icon-3d-fallback {
        width: 350px;
        height: 350px;
        border-radius: 24px;
        background: radial-gradient(circle at 30% 20%, #ffe7bf 0%, #ffd28a 42%, #ffb74d 100%);
        color: #232f3e;
        display: none;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 12px;
        animation: float 3s ease-in-out infinite;
        box-shadow: 0 10px 24px rgba(0, 0, 0, 0.14);
        text-align: center;
        padding: 20px;
    }

    .icon-3d-fallback i {
        font-size: 56px;
    }

    .icon-3d-fallback span {
        font-size: 15px;
        font-weight: 700;
        letter-spacing: 0.2px;
    }
    
    @keyframes float {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-15px); }
    }

    /* Page Footer */
    .page-footer {
        text-align: center;
        padding: 30px 20px;
        color: #666;
        font-size: 12px;
    }

    .page-footer a {
        color: #0066c0;
        text-decoration: none;
        margin: 0 10px;
    }

    .page-footer a:hover {
        text-decoration: underline;
    }

    .page-footer .copyright {
        margin-top: 10px;
        color: #999;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .main-container {
            flex-direction: column;
            align-items: center;
            gap: 30px;
            margin: 30px auto;
        }

        .form-card {
            width: 100%;
            max-width: 420px;
        }

        .icon-3d {
            width: 280px;
            height: 280px;
            order: -1;
        }

        .icon-3d-fallback {
            width: 280px;
            height: 280px;
            order: -1;
        }
    }
</style>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <div class="logo"><span>Seller</span> Central</div>
        <div class="header-links">
            <a href="/register"><i class="fas fa-user-plus"></i> Create Account</a>
            <a href="{{ route('help') }}"><i class="fas fa-question-circle"></i> Help</a>
        </div>
    </div>

    <div class="main-container">
        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h2><i class="fas fa-sign-in-alt"></i> Sign In</h2>
                <p>Welcome back! Sign in to manage your seller account</p>
            </div>

            <form action="/login" method="post">
                @csrf
                <div class="form-body">
                    <!-- Error Alert -->
                    @php $hasError = $errors->has('uname') || $errors->has('login'); @endphp
                    @if($hasError)
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="error-alert-content">
                            <h4>There was a problem</h4>
                            <p>{{ $errors->first('uname') ?: $errors->first('login') }}</p>
                        </div>
                    </div>
                    @endif

                    <div class="form-group">
                        <label for="uname">Mobile Number or Email <span class="required">*</span></label>
                        <div class="input-icon">
                            <i class="fas fa-user"></i>
                            <input type="text" id="uname" name="uname" value="{{ old('uname') }}" class="{{ $hasError ? 'input-error' : '' }}" placeholder="Enter mobile number or email" required autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="psw">Password <span class="required">*</span></label>
                        <div class="input-icon">
                            <i class="fas fa-lock"></i>
                            <input type="password" id="psw" name="psw" placeholder="Enter your password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-arrow-right"></i> Continue
                    </button>

                    <p class="legal-text">
                        By continuing, you agree to FrontStore's
                        <a href="{{ route('page.disclaimer') }}">Disclaimer</a> and
                        <a href="{{ route('page.privacy-policy') }}">Privacy Policy</a>.
                    </p>

                    <div class="help-link">
                        <a href="{{ route('page.help') }}"><i class="fas fa-question-circle"></i> Need help?</a>
                    </div>
                </div>
            </form>

            <div class="form-footer">
                <div class="divider">New to Seller Central?</div>
                <a href="/register" class="btn btn-secondary">
                    <i class="fas fa-user-plus"></i> Create your Seller account
                </a>
            </div>
        </div>

        <!-- 3D Icon -->
        <div>
            <img
                src="{{ asset('images/seller-login-3d-icon.svg') }}"
                alt="3D Shopping Icon"
                class="icon-3d"
                onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
            >
            <div class="icon-3d-fallback">
                <i class="fas fa-store"></i>
                <span>FrontStore Seller Central</span>
            </div>
        </div>
    </div>

    <div class="page-footer">
        <a href="{{ route('page.return-policy') }}">Return Policy</a>
        <a href="{{ route('page.refund-policy') }}">Refund Policy</a>
        <a href="{{ route('page.privacy-policy') }}">Privacy Policy</a>
        <a href="{{ route('page.disclaimer') }}">Disclaimer</a>
        <a href="{{ route('page.help') }}">Help</a>
        <div class="copyright">© 1996-2025, Seller Central, Inc. or its affiliates</div>
    </div>

</body>
</html>