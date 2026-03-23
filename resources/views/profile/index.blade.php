<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }

        /* Page Header */
        .page-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 25px;
        }

        .page-header i {
            font-size: 28px;
            color: #ff9900;
        }

        .page-header h1 {
            font-size: 28px;
            font-weight: 700;
            color: #0f1111;
        }

        /* Profile Section */
        .profile-section {
            display: grid;
            grid-template-columns: 350px 1fr;
            gap: 25px;
            margin-bottom: 30px;
        }

        /* Profile Card */
        .profile-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
            padding: 30px;
            text-align: center;
            position: relative;
        }

        .profile-avatar {
            width: 100px;
            height: 100px;
            background: linear-gradient(135deg, #ff9900, #ffad33);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
            border: 4px solid rgba(255,255,255,0.3);
        }

        .profile-avatar i {
            font-size: 45px;
            color: #fff;
        }

        .profile-name {
            color: #fff;
            font-size: 22px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .profile-email {
            color: rgba(255,255,255,0.8);
            font-size: 14px;
        }

        .profile-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255,255,255,0.15);
            padding: 6px 14px;
            border-radius: 20px;
            margin-top: 12px;
            font-size: 12px;
            color: #febd69;
        }

        .profile-body {
            padding: 25px;
        }

        .profile-info-item {
            display: flex;
            align-items: center;
            gap: 15px;
            padding: 15px 0;
            border-bottom: 1px solid #f0f0f0;
        }

        .profile-info-item:last-child {
            border-bottom: none;
        }

        .info-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #fff8e8, #fff3d6);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .info-icon i {
            font-size: 18px;
            color: #ff9900;
        }

        .info-content {
            flex: 1;
        }

        .info-label {
            font-size: 12px;
            color: #888;
            margin-bottom: 3px;
        }

        .info-value {
            font-size: 15px;
            color: #0f1111;
            font-weight: 500;
        }

        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 15px;
        }

        .stat-card {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s ease;
            text-decoration: none;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }

        .stat-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, #fff8e8, #fff3d6);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 15px;
        }

        .stat-icon i {
            font-size: 26px;
            color: #ff9900;
        }

        .stat-value {
            font-size: 32px;
            font-weight: 700;
            color: #0f1111;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 14px;
            color: #565959;
        }

        /* Quick Actions */
        .quick-actions-title {
            font-size: 20px;
            font-weight: 600;
            color: #0f1111;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .quick-actions-title i {
            color: #ff9900;
        }

        /* Account Grid */
        .account-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 18px;
        }

        /* Account Card */
        .account-card {
            background: #fff;
            border-radius: 12px;
            padding: 22px 25px;
            display: flex;
            align-items: flex-start;
            gap: 18px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            border: 1px solid #e0e0e0;
            box-shadow: 0 2px 8px rgba(0,0,0,0.04);
        }
        .account-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.1);
            border-color: #febd69;
        }

        /* Card Icon */
        .card-icon {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: linear-gradient(135deg, #fff8e8, #fff3d6);
            border-radius: 12px;
        }
        .card-icon i {
            font-size: 22px;
            color: #ff9900;
        }

        /* Card Content */
        .card-content {
            flex: 1;
        }
        .card-title {
            color: #0f1111;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }
        .card-desc {
            color: #565959;
            font-size: 13px;
            line-height: 1.4;
        }

        /* Logout Card Special Style */
        .account-card.logout {
            border-color: #fee2e2;
            background: #fff5f5;
        }

        .account-card.logout:hover {
            border-color: #fca5a5;
            background: #fef2f2;
        }

        .account-card.logout .card-icon {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
        }

        .account-card.logout .card-icon i {
            color: #dc2626;
        }

        /* Responsive */
        @media (max-width: 1000px) {
            .profile-section {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 900px) {
            .account-grid {
                grid-template-columns: repeat(2, 1fr);
            }

            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 600px) {
            .account-grid {
                grid-template-columns: 1fr;
            }
            .page-header h1 {
                font-size: 24px;
            }

            .stats-grid {
                grid-template-columns: 1fr 1fr;
            }
        }
    </style>
</head>
<body>

    @include('shop.partials.navbar')

    <div class="container">
        
        <div class="page-header">
            <i class="fas fa-user-circle"></i>
            <h1>My Account</h1>
        </div>

        <!-- Profile Section -->
        <div class="profile-section">
            <!-- Profile Card -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-name">{{ $customer->name ?? 'Customer' }}</div>
                    <div class="profile-email">{{ $customer->email ?? session('customer_email') }}</div>
                    <div class="profile-badge">
                        <i class="fas fa-check-circle"></i> Verified Customer
                    </div>
                </div>
                <div class="profile-body">
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Full Name</div>
                            <div class="info-value">{{ $customer->name ?? 'Not set' }}</div>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Email Address</div>
                            <div class="info-value">{{ $customer->email ?? session('customer_email') }}</div>
                        </div>
                    </div>
                    <div class="profile-info-item">
                        <div class="info-icon">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div class="info-content">
                            <div class="info-label">Member Since</div>
                            <div class="info-value">{{ $customer->created_at ? $customer->created_at->format('M d, Y') : 'N/A' }}</div>
                        </div>
                    </div>
                    <button class="edit-profile-btn" onclick="openEditProfileModal()"><i class="fas fa-edit"></i> Edit Profile</button>
                </div>
                <!-- Edit Profile Modal -->
                <div class="modal-overlay" id="editProfileModal">
                    <div class="modal">
                        <div class="modal-header">
                            <h3><i class="fas fa-user-edit"></i> Edit Profile</h3>
                            <button class="modal-close" onclick="closeEditProfileModal()">&times;</button>
                        </div>
                        <form id="editProfileForm" method="POST" action="{{ route('profile.update') }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-body">
                                <div class="form-group">
                                    <label for="edit_name"><i class="fas fa-user"></i> Full Name</label>
                                    <input type="text" name="name" id="edit_name" value="{{ $customer->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_email"><i class="fas fa-envelope"></i> Email Address</label>
                                    <input type="email" name="email" id="edit_email" value="{{ $customer->email }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="edit_password"><i class="fas fa-lock"></i> New Password <span style="color:#888;font-size:12px;">(leave blank to keep current)</span></label>
                                    <input type="password" name="password" id="edit_password" autocomplete="new-password">
                                </div>
                                <div class="form-group">
                                    <label for="edit_password_confirmation"><i class="fas fa-lock"></i> Confirm Password</label>
                                    <input type="password" name="password_confirmation" id="edit_password_confirmation" autocomplete="new-password">
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" onclick="closeEditProfileModal()">Cancel</button>
                                <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Save Changes</button>
                            </div>
                        </form>
                    </div>
                </div>
                <style>
                    .edit-profile-btn {
                        margin-top: 18px;
                        background: linear-gradient(135deg, #ff9900, #febd69);
                        color: #232f3e;
                        border: none;
                        border-radius: 8px;
                        padding: 10px 22px;
                        font-size: 15px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all 0.2s;
                        display: flex;
                        align-items: center;
                        gap: 8px;
                    }
                    .edit-profile-btn:hover {
                        background: linear-gradient(135deg, #febd69, #ff9900);
                        color: #232f3e;
                        transform: translateY(-2px);
                    }
                    .modal-overlay {
                        position: fixed;
                        top: 0; left: 0; right: 0; bottom: 0;
                        background: rgba(0,0,0,0.25);
                        display: none;
                        align-items: center;
                        justify-content: center;
                        z-index: 9999;
                    }
                    .modal-overlay.active { display: flex; }
                    .modal {
                        background: #fff;
                        border-radius: 16px;
                        max-width: 420px;
                        width: 100%;
                        box-shadow: 0 8px 32px rgba(0,0,0,0.18);
                        overflow: hidden;
                        animation: fadeIn 0.2s;
                    }
                    @keyframes fadeIn { from { opacity: 0; transform: scale(0.98);} to { opacity: 1; transform: scale(1);} }
                    .modal-header {
                        background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
                        color: #fff;
                        padding: 22px 28px;
                        display: flex;
                        align-items: center;
                        justify-content: space-between;
                    }
                    .modal-header h3 { font-size: 20px; font-weight: 600; display: flex; align-items: center; gap: 10px; }
                    .modal-close {
                        background: none;
                        border: none;
                        color: #fff;
                        font-size: 26px;
                        cursor: pointer;
                    }
                    .modal-body { padding: 24px 28px 0 28px; }
                    .form-group { margin-bottom: 18px; }
                    .form-group label { display: block; font-size: 14px; color: #232f3e; margin-bottom: 6px; font-weight: 500; }
                    .form-group input {
                        width: 100%;
                        padding: 12px 14px;
                        border: 1.5px solid #e0e0e0;
                        border-radius: 8px;
                        font-size: 15px;
                        transition: border 0.2s;
                    }
                    .form-group input:focus { border-color: #ff9900; outline: none; }
                    .modal-footer {
                        padding: 18px 28px 24px 28px;
                        display: flex;
                        justify-content: flex-end;
                        gap: 12px;
                    }
                    .btn.btn-secondary {
                        background: #e0e0e0;
                        color: #232f3e;
                        border: none;
                        border-radius: 8px;
                        padding: 10px 20px;
                        font-weight: 600;
                        cursor: pointer;
                    }
                    .btn.btn-primary {
                        background: linear-gradient(135deg, #ff9900, #febd69);
                        color: #232f3e;
                        border: none;
                        border-radius: 8px;
                        padding: 10px 22px;
                        font-weight: 600;
                        cursor: pointer;
                    }
                    .btn.btn-primary:hover {
                        background: linear-gradient(135deg, #febd69, #ff9900);
                    }
                </style>
                <script>
                    function openEditProfileModal() {
                        document.getElementById('editProfileModal').classList.add('active');
                    }
                    function closeEditProfileModal() {
                        document.getElementById('editProfileModal').classList.remove('active');
                    }
                    // Close modal on outside click
                    window.addEventListener('click', function(e) {
                        const modal = document.getElementById('editProfileModal');
                        if (modal && e.target === modal) closeEditProfileModal();
                    });
                </script>
            </div>

            <!-- Stats Section -->
            <div>
                <div class="stats-grid">
                    <a href="{{ route('profile.orders') }}" class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-box"></i>
                        </div>
                        <div class="stat-value">{{ $orderCount ?? 0 }}</div>
                        <div class="stat-label">Total Orders</div>
                    </a>
                    <a href="{{ route('profile.reviews') }}" class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-star"></i>
                        </div>
                        <div class="stat-value">{{ $reviewCount ?? 0 }}</div>
                        <div class="stat-label">Reviews Written</div>
                    </a>
                    <a href="{{ route('shop.cart') }}" class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <div class="stat-value">{{ count(session('cart', [])) }}</div>
                        <div class="stat-label">Cart Items</div>
                    </a>
                    <a href="#" class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-heart"></i>
                        </div>
                        <div class="stat-value">0</div>
                        <div class="stat-label">Wishlist Items</div>
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <h2 class="quick-actions-title"><i class="fas fa-bolt"></i> Quick Actions</h2>

        <div class="account-grid">
            
            <!-- My Orders -->
            <a href="{{ route('profile.orders') }}" class="account-card">
                <div class="card-icon">
                    <i class="fas fa-box"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">My Orders</div>
                    <div class="card-desc">View, track, cancel orders and buy again</div>
                </div>
            </a>

            <!-- My Address -->
            <a href="{{ route('profile.addresses') }}" class="account-card">
                <div class="card-icon">
                    <i class="far fa-address-book"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">My Address</div>
                    <div class="card-desc">Manage your saved addresses</div>
                </div>
            </a>

            <!-- My Reviews -->
            <a href="{{ route('profile.reviews') }}" class="account-card">
                <div class="card-icon">
                    <i class="far fa-star"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">My Reviews</div>
                    <div class="card-desc">View and manage your product reviews</div>
                </div>
            </a>

            <!-- My Returns -->
            <a href="{{ route('returns.index') }}" class="account-card">
                <div class="card-icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">My Returns</div>
                    <div class="card-desc">Track return requests and refunds</div>
                </div>
            </a>

            <!-- My Cart -->
            <a href="{{ route('shop.cart') }}" class="account-card">
                <div class="card-icon">
                    <i class="fas fa-shopping-cart"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">My Cart</div>
                    <div class="card-desc">View items and proceed to checkout</div>
                </div>
            </a>

            <!-- Help & Support -->
            <a href="{{ route('shop.help') }}" class="account-card">
                <div class="card-icon">
                    <i class="far fa-comment-alt"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Help & Support</div>
                    <div class="card-desc">Manage complaints, feedback, service requests</div>
                </div>
            </a>

            <!-- Logout -->
            <a href="{{ route('customer.logout') }}" class="account-card logout">
                <div class="card-icon">
                    <i class="fas fa-sign-out-alt"></i>
                </div>
                <div class="card-content">
                    <div class="card-title">Logout</div>
                    <div class="card-desc">Sign out from your account</div>
                </div>
            </a>

        </div>

    </div>

    @include('shop.partials.footer')

</body>
</html>
