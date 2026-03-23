<nav class="navbar">
    <div class="nav-container">
        <a href="{{ route('shop.index') }}" class="nav-logo">
            <span class="logo-text">Front</span><span class="logo-accent">Store</span>
        </a>
        
        <form action="{{ route('shop.index') }}" method="GET" class="search-bar">
            <div class="search-wrapper">
                <input type="text" name="search" placeholder="Search products..." value="{{ request('search') }}">
                <button type="submit"><i class="fas fa-search"></i></button>
            </div>
        </form>
        
        <div class="nav-links">
            <a href="{{ route('profile.index') }}" class="nav-link">
                <div class="nav-icon"><i class="fas fa-user"></i></div>
                <div class="nav-text">
                    <span class="nav-label">Hello, User</span>
                    <span class="nav-title">My Account</span>
                </div>
            </a>
            
            <a href="{{ route('profile.orders') }}" class="nav-link">
                <div class="nav-icon"><i class="fas fa-box"></i></div>
                <div class="nav-text">
                    <span class="nav-label"></span>
                    <span class="nav-title">Orders</span>
                </div>
            </a>
            
            <a href="{{ route('shop.cart') }}" class="nav-link cart-link">
                <div class="cart-icon-wrapper">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="cart-badge" id="cart-count">{{ session('cart') ? count(session('cart')) : 0 }}</span>
                </div>
                <span class="cart-text">Cart</span>
            </a>
        </div>

        <button class="mobile-menu-btn" onclick="toggleMobileMenu()">
            <i class="fas fa-bars"></i>
        </button>
    </div>
</nav>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <a href="{{ route('shop.index') }}" class="mobile-link"><i class="fas fa-home"></i> Home</a>
    <a href="{{ route('profile.index') }}" class="mobile-link"><i class="fas fa-user"></i> My Account</a>
    <a href="{{ route('profile.orders') }}" class="mobile-link"><i class="fas fa-box"></i> My Orders</a>
    <a href="{{ route('shop.cart') }}" class="mobile-link"><i class="fas fa-shopping-cart"></i> Cart</a>
</div>

<style>
    /* Navbar - Modern Theme */
    .navbar {
        background: linear-gradient(90deg, #232f3e 0%, #37475a 100%);
        padding: 0;
        position: sticky;
        top: 0;
        z-index: 1000;
        box-shadow: 0 2px 10px rgba(0,0,0,0.15);
    }

    .nav-container {
        max-width: 1400px;
        margin: 0 auto;
        display: flex;
        align-items: center;
        padding: 12px 25px;
        gap: 25px;
    }

    /* Logo */
    .nav-logo {
        text-decoration: none;
        display: flex;
        align-items: baseline;
        padding: 8px 12px;
        border-radius: 4px;
        transition: all 0.2s;
    }

    .nav-logo:hover {
        background: rgba(255,255,255,0.1);
    }

    .logo-text {
        font-family: 'Dancing Script', cursive;
        font-size: 30px;
        font-weight: 700;
        color: #fff;
    }

    .logo-accent {
        font-family: 'Dancing Script', cursive;
        font-size: 18px;
        font-weight: 500;
        color: #febd69;
        margin-left: 2px;
    }

    /* Search Bar */
    .search-bar {
        flex: 1;
        max-width: 650px;
    }

    .search-wrapper {
        display: flex;
        border-radius: 8px;
        overflow: hidden;
        background: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .search-wrapper input {
        flex: 1;
        padding: 12px 18px;
        border: none;
        font-size: 14px;
        background: transparent;
    }

    .search-wrapper input:focus {
        outline: none;
    }

    .search-wrapper button {
        background: linear-gradient(135deg, #febd69, #f3a847);
        border: none;
        padding: 12px 20px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .search-wrapper button:hover {
        background: linear-gradient(135deg, #f3a847, #e09b3d);
    }

    .search-wrapper button i {
        color: #232f3e;
        font-size: 16px;
    }

    /* Nav Links */
    .nav-links {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .nav-link {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        border-radius: 4px;
        text-decoration: none;
        color: #fff;
        transition: all 0.2s;
    }

    .nav-link:hover {
        background: rgba(255,255,255,0.1);
    }

    .nav-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255,255,255,0.1);
        border-radius: 8px;
    }

    .nav-icon i {
        font-size: 14px;
        color: #febd69;
    }

    .nav-text {
        display: flex;
        flex-direction: column;
    }

    .nav-label {
        font-size: 11px;
        color: rgba(255,255,255,0.7);
    }

    .nav-title {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
    }

    /* Cart Link */
    .cart-link {
        gap: 6px;
    }

    .cart-icon-wrapper {
        position: relative;
    }

    .cart-icon-wrapper i {
        font-size: 24px;
        color: #fff;
    }

    .cart-badge {
        position: absolute;
        top: -8px;
        right: -10px;
        background: linear-gradient(135deg, #ff9900, #ffad33);
        color: #fff;
        font-size: 11px;
        font-weight: 700;
        min-width: 20px;
        height: 20px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 0 5px;
    }

    .cart-text {
        font-size: 13px;
        font-weight: 600;
        color: #fff;
    }

    /* Mobile Menu Button */
    .mobile-menu-btn {
        display: none;
        background: rgba(255,255,255,0.1);
        border: none;
        padding: 10px 14px;
        border-radius: 8px;
        cursor: pointer;
        color: #fff;
        font-size: 18px;
    }

    /* Mobile Menu */
    .mobile-menu {
        display: none;
        background: #232f3e;
        padding: 15px;
        flex-direction: column;
        gap: 5px;
    }

    .mobile-menu.active {
        display: flex;
    }

    .mobile-link {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 15px;
        color: #fff;
        text-decoration: none;
        border-radius: 8px;
        transition: all 0.2s;
    }

    .mobile-link:hover {
        background: rgba(255,255,255,0.1);
    }

    .mobile-link i {
        width: 20px;
        color: #febd69;
    }

    /* Responsive */
    @media (max-width: 900px) {
        .nav-text {
            display: none;
        }
        
        .nav-icon {
            width: 40px;
            height: 40px;
        }

        .nav-icon i {
            font-size: 16px;
        }
    }

    @media (max-width: 700px) {
        .nav-links {
            display: none;
        }

        .mobile-menu-btn {
            display: block;
        }

        .search-bar {
            max-width: none;
        }

        .nav-container {
            padding: 12px 15px;
            gap: 15px;
        }
    }
</style>

<script>
    function toggleMobileMenu() {
        const menu = document.getElementById('mobileMenu');
        menu.classList.toggle('active');
    }
</script>
