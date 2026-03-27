<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>Seller Central - Create Account</title>
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
        margin: 30px auto;
        padding: 0 20px;
    }

    /* Progress Steps */
    .progress-container {
        display: flex;
        justify-content: center;
        margin-bottom: 30px;
    }

    .progress-steps {
        display: flex;
        align-items: center;
        background: #fff;
        padding: 20px 40px;
        border-radius: 50px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    }

    .step {
        display: flex;
        align-items: center;
        cursor: pointer;
    }

    .step-number {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: #e0e0e0;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 14px;
        transition: all 0.3s;
    }

    .step.active .step-number {
        background: linear-gradient(135deg, #ff9900, #ffad33);
        color: #fff;
        box-shadow: 0 4px 15px rgba(255, 153, 0, 0.4);
    }

    .step.completed .step-number {
        background: #00a650;
        color: #fff;
    }

    .step-label {
        margin-left: 10px;
        font-size: 13px;
        font-weight: 600;
        color: #999;
        transition: all 0.3s;
    }

    .step.active .step-label,
    .step.completed .step-label {
        color: #333;
    }

    .step-connector {
        width: 60px;
        height: 3px;
        background: #e0e0e0;
        margin: 0 15px;
        transition: all 0.3s;
    }

    .step-connector.completed {
        background: #00a650;
    }

    /* Form Card */
    .form-card {
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
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
    }

    .form-header p {
        font-size: 13px;
        opacity: 0.8;
    }

    .form-body {
        padding: 30px;
    }

    /* Step Content */
    .step-content {
        display: none;
        animation: fadeIn 0.4s ease;
    }

    .step-content.active {
        display: block;
    }

    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(10px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* Form Groups */
    .form-row {
        display: flex;
        gap: 20px;
        margin-bottom: 20px;
    }

    .form-group {
        flex: 1;
        margin-bottom: 20px;
    }

    .form-group.half {
        flex: 0 0 calc(50% - 10px);
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

    .form-group input,
    .form-group select,
    .form-group textarea {
        width: 100%;
        padding: 12px 15px;
        border: 1px solid #d5d9d9;
        border-radius: 8px;
        font-size: 14px;
        font-family: inherit;
        transition: all 0.2s;
        background: #fff;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        border-color: #ff9900;
        box-shadow: 0 0 0 3px rgba(255, 153, 0, 0.15);
        outline: none;
    }

    .form-group textarea {
        resize: vertical;
        min-height: 80px;
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

    .form-group .helper-text {
        font-size: 11px;
        color: #666;
        margin-top: 5px;
    }

    .form-group.error input {
        border-color: #c40000;
    }

    .form-group .error-text {
        color: #c40000;
        font-size: 12px;
        margin-top: 5px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    /* Phone Input */
    .phone-input {
        display: flex;
        gap: 10px;
    }

    .phone-input select {
        width: 110px;
        flex-shrink: 0;
    }

    .phone-input input {
        flex: 1;
    }

    /* Section Title */
    .section-title {
        font-size: 16px;
        font-weight: 600;
        color: #232f3e;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #ff9900;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .section-title i {
        color: #ff9900;
    }

    /* Info Box */
    .info-box {
        background: #f0f8ff;
        border: 1px solid #b8daff;
        border-radius: 8px;
        padding: 15px;
        margin-bottom: 25px;
        display: flex;
        align-items: flex-start;
        gap: 12px;
    }

    .info-box i {
        color: #0066c0;
        font-size: 18px;
        margin-top: 2px;
    }

    .info-box p {
        font-size: 13px;
        color: #0066c0;
        line-height: 1.5;
    }

    /* Buttons */
    .form-actions {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin: 30px 30px 30px 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
    }

    .form-actions .btn-left {
        min-width: 120px;
    }

    .form-actions .btn-right {
        margin-left: auto;
    }

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
        gap: 8px;
    }

    .btn-secondary {
        background: #f5f5f5;
        color: #333;
        border: 1px solid #d5d9d9;
    }

    .btn-secondary:hover {
        background: #e8e8e8;
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

    .btn-success {
        background: linear-gradient(135deg, #00a650, #00c853);
        color: #fff;
        box-shadow: 0 4px 15px rgba(0, 166, 80, 0.3);
    }

    .btn-success:hover {
        transform: translateY(-1px);
    }

    /* Footer */
    .form-footer {
        text-align: center;
        padding: 20px;
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

    /* Responsive */
    @media (max-width: 768px) {
        .progress-steps {
            flex-wrap: wrap;
            padding: 15px 20px;
            border-radius: 12px;
        }

        .step-connector {
            display: none;
        }

        .step {
            margin: 5px;
        }

        .form-row {
            flex-direction: column;
            gap: 0;
        }

        .form-group.half {
            flex: 1;
        }
    }
</style>
</head>
<body>
    
    <!-- Header -->
    <div class="header">
        <div class="logo"><span>Seller</span> Central</div>
        <div class="header-links">
            <a href="{{ url('/login') }}"><i class="fas fa-sign-in-alt"></i> Sign In</a>
            <a href="{{ route('help') }}"><i class="fas fa-question-circle"></i> Help</a>
        </div>
    </div>

    <div class="main-container">
        <!-- Progress Steps -->
        <div class="progress-container">
            <div class="progress-steps">
                <div class="step active" data-step="1">
                    <div class="step-number">1</div>
                    <div class="step-label">Account</div>
                </div>
                <div class="step-connector"></div>
                <div class="step" data-step="2">
                    <div class="step-number">2</div>
                    <div class="step-label">Business</div>
                </div>
                <div class="step-connector"></div>
                <div class="step" data-step="3">
                    <div class="step-number">3</div>
                    <div class="step-label">Tax & Legal</div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="form-card">
            <div class="form-header">
                <h2><i class="fas fa-user-plus"></i> Create Your Seller Account</h2>
                <p>Join thousands of successful sellers on our platform</p>
            </div>

            <form action="{{ route('register.store') }}" method="post" id="registerForm">
                @csrf

                <div class="form-body">
                    <!-- Error Alert -->
                    @if($errors->any())
                    <div class="error-alert">
                        <i class="fas fa-exclamation-circle"></i>
                        <div class="error-alert-content">
                            <h4>There was a problem</h4>
                            <p>{{ $errors->first() }}</p>
                        </div>
                    </div>
                    @endif

                    <!-- Step 1: Account Information -->
                    <div class="step-content active" id="step1">
                        <div class="section-title">
                            <i class="fas fa-user"></i> Account Information
                        </div>

                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Create your account credentials. This information will be used to login to your seller dashboard.</p>
                        </div>

                        <div class="form-group">
                            <label for="name">Your Full Name <span class="required">*</span></label>
                            <div class="input-icon">
                                <i class="fas fa-user"></i>
                                <input type="text" id="name" name="name" value="{{ old('name') }}" placeholder="Enter your full name" required>
                            </div>
                            @if($errors->has('name'))
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('name') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="phone">Mobile Number <span class="required">*</span></label>
                            <div class="phone-input">
                                <select name="country_code" id="country_code">
                                    <option value="IN">🇮🇳 +91</option>
                                    <option value="US">🇺🇸 +1</option>
                                    <option value="GB">🇬🇧 +44</option>
                                    <option value="AE">🇦🇪 +971</option>
                                </select>
                                <input type="tel" id="phone" name="phone" value="{{ old('phone') }}" placeholder="Enter 10-digit mobile number" maxlength="10" required>
                            </div>
                            @if($errors->has('phone'))
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('phone') }}</div>
                            @endif
                            <!-- <div class="helper-text">We'll send an OTP to verify your number</div> -->
                        </div>

                        <div class="form-group">
                            <label for="email">Email Address</label>
                            <div class="input-icon">
                                <i class="fas fa-envelope"></i>
                                <input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="Enter your email address">
                            </div>
                            <div class="helper-text">Optional, but recommended for order notifications</div>
                        </div>

                        <div class="form-group">
                            <label for="password">Create Password <span class="required">*</span></label>
                            <div class="input-icon">
                                <i class="fas fa-lock"></i>
                                <input type="password" id="password" name="password" placeholder="Create a strong password" required>
                            </div>
                            @if($errors->has('password'))
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('password') }}</div>
                            @endif
                            <div class="helper-text">At least 6 characters</div>
                        </div>
                    </div>

                    <!-- Step 2: Business Details -->
                    <div class="step-content" id="step2">
                        <div class="section-title">
                            <i class="fas fa-store"></i> Business Information
                        </div>

                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>Enter your business details. This information will appear on invoices sent to your customers.</p>
                        </div>

                        <div class="form-group">
                            <label for="business_name">Business / Shop Name <span class="required">*</span></label>
                            <div class="input-icon">
                                <i class="fas fa-building"></i>
                                <input type="text" id="business_name" name="business_name" value="{{ old('business_name') }}" placeholder="e.g., Business name">
                            </div>
                            @if($errors->has('business_name'))
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('business_name') }}</div>
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="business_address">Business Address <span class="required">*</span></label>
                            <textarea id="business_address" name="business_address" placeholder="e.g., Enter your complete business address">{{ old('business_address') }}</textarea>
                            @if($errors->has('business_address'))
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $errors->first('business_address') }}</div>
                            @endif
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label for="city">City <span class="required">*</span></label>
                                <input type="text" id="city" name="city" value="{{ old('city') }}" placeholder="e.g., Enter your city name">
                            </div>
                            <div class="form-group half">
                                <label for="state">State <span class="required">*</span></label>
                                <input type="text" id="state" name="state" value="{{ old('state') }}" placeholder="e.g., Enter your state name">
                            </div>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label for="pincode">Pincode <span class="required">*</span></label>
                                <input type="text" id="pincode" name="pincode" value="{{ old('pincode') }}" placeholder="e.g., Enter your pincode" maxlength="6">
                            </div>
                            <div class="form-group half">
                                <label for="country">Country</label>
                                <select id="country" name="country">
                                    <option value="India" {{ old('country', 'India') == 'India' ? 'selected' : '' }}>🇮🇳 India</option>
                                    <option value="USA" {{ old('country') == 'USA' ? 'selected' : '' }}>🇺🇸 USA</option>
                                    <option value="UK" {{ old('country') == 'UK' ? 'selected' : '' }}>🇬🇧 UK</option>
                                    <option value="UAE" {{ old('country') == 'UAE' ? 'selected' : '' }}>🇦🇪 UAE</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Tax & Legal -->
                    <div class="step-content" id="step3">
                        <div class="section-title">
                            <i class="fas fa-file-invoice"></i> Tax & Legal Details
                        </div>

                        <div class="info-box">
                            <i class="fas fa-info-circle"></i>
                            <p>These details are optional but recommended. They will appear on your invoices for tax compliance.</p>
                        </div>

                        <div class="form-group">
                            <label for="gstin">GSTIN (GST Number)</label>
                            <div class="input-icon">
                                <i class="fas fa-receipt"></i>
                                <input type="text" id="gstin" name="gstin" value="{{ old('gstin') }}" placeholder="e.g., 24BHFPR1987N1ZN" maxlength="15" style="text-transform: uppercase;">
                            </div>
                            <div class="helper-text">15-character GST Identification Number (e.g., 24BHFPR1987N1ZN)</div>
                        </div>

                        <div class="form-group">
                            <label for="pan">PAN Number</label>
                            <div class="input-icon">
                                <i class="fas fa-id-card"></i>
                                <input type="text" id="pan" name="pan" value="{{ old('pan') }}" placeholder="e.g., BHFPR1987N" maxlength="10" style="text-transform: uppercase;">
                            </div>
                            <div class="helper-text">10-character Permanent Account Number</div>
                        </div>

                        <div class="form-group">
                            <label for="cin">CIN (Company Identification Number)</label>
                            <div class="input-icon">
                                <i class="fas fa-landmark"></i>
                                <input type="text" id="cin" name="cin" value="{{ old('cin') }}" placeholder="e.g., U74999GJ2024PTC123456" maxlength="21" style="text-transform: uppercase;">
                            </div>
                            <div class="helper-text">Required only if you are a registered Private Limited company</div>
                        </div>

                        <div style="background: #f0fff4; border: 1px solid #9ae6b4; border-radius: 8px; padding: 15px; margin-top: 20px;">
                            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                                <i class="fas fa-check-circle" style="color: #00a650; font-size: 20px;"></i>
                                <strong style="color: #22543d;">Almost Done!</strong>
                            </div>
                            <p style="font-size: 13px; color: #22543d; margin: 0;">
                                Click "Create Account" to complete your registration. You can always update your tax details later from your profile settings.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="form-actions">
                    <div class="btn-left">
                        <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">
                            <i class="fas fa-arrow-left"></i> Previous
                        </button>
                    </div>
                    <div class="btn-right">
                        <button type="button" class="btn btn-primary" id="nextBtn">
                            Next Step <i class="fas fa-arrow-right"></i>
                        </button>
                        <button type="submit" class="btn btn-success" id="submitBtn" style="display: none;">
                            <i class="fas fa-check"></i> Create Account
                        </button>
                    </div>
                </div>

                <div style="margin: 0 30px 20px 30px; font-size: 12px; color: #6b7280; line-height: 1.7;">
                    By creating a seller account, you agree to the
                    <a href="{{ route('page.return-policy') }}" style="color:#0066c0;text-decoration:none;font-weight:600;">Return Policy</a>,
                    <a href="{{ route('page.refund-policy') }}" style="color:#0066c0;text-decoration:none;font-weight:600;">Refund Policy</a>,
                    <a href="{{ route('page.privacy-policy') }}" style="color:#0066c0;text-decoration:none;font-weight:600;">Privacy Policy</a>, and
                    <a href="{{ route('page.disclaimer') }}" style="color:#0066c0;text-decoration:none;font-weight:600;">Disclaimer</a>.
                </div>
            </form>

            <!-- Footer -->
            <div class="form-footer">
                <p>Already have an account? <a href="{{ url('/login') }}">Sign In</a></p>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 3;

        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        const submitBtn = document.getElementById('submitBtn');

        function updateStep(step) {
            // Update step content visibility
            document.querySelectorAll('.step-content').forEach((el, index) => {
                el.classList.remove('active');
                if (index + 1 === step) {
                    el.classList.add('active');
                }
            });

            // Update progress steps
            document.querySelectorAll('.step').forEach((el, index) => {
                el.classList.remove('active', 'completed');
                if (index + 1 < step) {
                    el.classList.add('completed');
                } else if (index + 1 === step) {
                    el.classList.add('active');
                }
            });

            // Update connectors
            document.querySelectorAll('.step-connector').forEach((el, index) => {
                el.classList.remove('completed');
                if (index + 1 < step) {
                    el.classList.add('completed');
                }
            });

            // Update buttons
            prevBtn.style.display = step > 1 ? 'flex' : 'none';
            nextBtn.style.display = step < totalSteps ? 'flex' : 'none';
            submitBtn.style.display = step === totalSteps ? 'flex' : 'none';

            // Update form header
            const headers = [
                { title: 'Account Information', desc: 'Create your login credentials' },
                { title: 'Business Details', desc: 'Tell us about your business' },
                { title: 'Tax & Legal Details', desc: 'Complete your seller profile' }
            ];

            document.querySelector('.form-header h2').innerHTML = `<i class="fas fa-user-plus"></i> Step ${step}: ${headers[step-1].title}`;
            document.querySelector('.form-header p').textContent = headers[step-1].desc;
        }

        function validateStep(step) {
            let isValid = true;

            if (step === 1) {
                const name = document.getElementById('name').value.trim();
                const phone = document.getElementById('phone').value.trim();
                const password = document.getElementById('password').value;

                if (!name) {
                    alert('Please enter your name');
                    isValid = false;
                } else if (!phone || phone.length !== 10) {
                    alert('Please enter a valid 10-digit phone number');
                    isValid = false;
                } else if (!password || password.length < 6) {
                    alert('Password must be at least 6 characters');
                    isValid = false;
                }
            }

            if (step === 2) {
                const businessName = document.getElementById('business_name').value.trim();
                const businessAddress = document.getElementById('business_address').value.trim();
                const city = document.getElementById('city').value.trim();
                const state = document.getElementById('state').value.trim();
                const pincode = document.getElementById('pincode').value.trim();

                if (!businessName) {
                    alert('Please enter your business name');
                    isValid = false;
                } else if (!businessAddress) {
                    alert('Please enter your business address');
                    isValid = false;
                } else if (!city) {
                    alert('Please enter your city');
                    isValid = false;
                } else if (!state) {
                    alert('Please enter your state');
                    isValid = false;
                } else if (!pincode) {
                    alert('Please enter your pincode');
                    isValid = false;
                }
            }

            return isValid;
        }

        nextBtn.addEventListener('click', function() {
            if (validateStep(currentStep)) {
                if (currentStep < totalSteps) {
                    currentStep++;
                    updateStep(currentStep);
                }
            }
        });

        prevBtn.addEventListener('click', function() {
            if (currentStep > 1) {
                currentStep--;
                updateStep(currentStep);
            }
        });

        // Allow clicking on completed steps to go back
        document.querySelectorAll('.step').forEach((el) => {
            el.addEventListener('click', function() {
                const stepNum = parseInt(this.dataset.step);
                if (stepNum < currentStep) {
                    currentStep = stepNum;
                    updateStep(currentStep);
                }
            });
        });

        // Phone number validation
        document.getElementById('phone').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 10);
        });

        // Pincode validation
        document.getElementById('pincode').addEventListener('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '').slice(0, 6);
        });

        // Uppercase for tax fields
        ['gstin', 'pan', 'cin'].forEach(id => {
            const el = document.getElementById(id);
            if (el) {
                el.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });
            }
        });

        // Check for validation errors and jump to appropriate step
        @if($errors->any())
            const errorFields = {!! json_encode($errors->keys()) !!};
            const step1Fields = ['name', 'phone', 'email', 'password', 'country_code'];
            const step2Fields = ['business_name', 'business_address', 'city', 'state', 'pincode', 'country'];
            
            if (errorFields.some(f => step2Fields.includes(f))) {
                currentStep = 2;
                updateStep(2);
            } else if (errorFields.some(f => ['gstin', 'pan', 'cin'].includes(f))) {
                currentStep = 3;
                updateStep(3);
            }
        @endif
    </script>

</body>
</html>
