
<footer class="frontstore-footer">
    <div class="footer-top-border"></div>
    <div class="footer-main">
        <div class="footer-brand-block">
            <div class="footer-logo"><i class="fas fa-store"></i> <span class="footer-logo-front">Front</span><span class="footer-logo-store">Store</span></div>
            <div class="footer-brand-desc">Quality products • Fast shipping • Secure payments</div>
        </div>
        <div class="footer-links-grid">
            <div class="footer-links-col">
                <div class="footer-col-title">Navigate</div>
                <a href="{{ route('shop.index') }}" class="footer-link"><i class="fas fa-home"></i> Home</a>
                <a href="{{ route('profile.orders') }}" class="footer-link"><i class="fas fa-box"></i> My Orders</a>
                <a href="{{ route('shop.cart') }}" class="footer-link"><i class="fas fa-shopping-cart"></i> Cart</a>
                <a href="{{ route('shop.help') }}" class="footer-link"><i class="fas fa-life-ring"></i> Help Center</a>
            </div>
            <div class="footer-links-col">
                <div class="footer-col-title">Customer Care</div>
                <a href="mailto:frontstore.team@outlook.com" class="footer-link"><i class="fas fa-envelope"></i> frontstore.team@outlook.com</a>
                <a href="tel:1800-123-4567" class="footer-link"><i class="fas fa-phone"></i> 1800-123-4567</a>
                <a href="{{ route('page.return-policy') }}" class="footer-link"><i class="fas fa-undo-alt"></i> Return Policy</a>
                <a href="{{ route('page.refund-policy') }}" class="footer-link"><i class="fas fa-wallet"></i> Refund Policy</a>
                <a href="{{ route('page.privacy-policy') }}" class="footer-link"><i class="fas fa-user-shield"></i> Privacy Policy</a>
                <a href="{{ route('page.disclaimer') }}" class="footer-link"><i class="fas fa-scale-balanced"></i> Disclaimer</a>
            </div>
            <div class="footer-links-col">
                <div class="footer-col-title">Follow Us</div>
                <div class="footer-socials">
                    <a href="#" class="footer-social" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" class="footer-social" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" class="footer-social" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom-bar">
        <div>© {{ date('Y') }} FrontStore. All rights reserved.</div>
        <div class="footer-bottom-links">
            <a href="{{ route('page.privacy-policy') }}">Privacy</a>
            <span>•</span>
            <a href="{{ route('page.disclaimer') }}">Disclaimer</a>
            <span>•</span>
            <a href="{{ route('page.refund-policy') }}">Refund Policy</a>
        </div>
    </div>
    <div style="text-align:center; padding: 10px 0 14px; font-size: 11px; color: #a0aab3; letter-spacing: 0.3px;">
        Developed by <a href="https://bhavdeep.me" target="_blank" rel="noopener" style="color: #febd69; text-decoration: none; font-weight: 600;">bhavdeep.me</a>
    </div>
</footer>

<style>
    .frontstore-footer {
        background: linear-gradient(135deg, #232f3e 0%, #37475a 100%);
        color: #e6edf3;
        border-radius: 18px 18px 0 0;
        margin-top: 48px;
        box-shadow: 0 -2px 16px rgba(35,47,62,0.08);
        overflow: hidden;
        position: relative;
    }
    .footer-top-border {
        height: 5px;
        background: linear-gradient(90deg, #ff9900, #febd69, #ff9900);
        width: 100%;
    }
    .footer-main {
        max-width: 1200px;
        margin: 0 auto;
        padding: 38px 20px 18px 20px;
        display: flex;
        flex-direction: column;
        gap: 32px;
    }
    .footer-brand-block {
        display: flex;
        flex-direction: column;
        align-items: flex-start;
        gap: 6px;
    }
    .footer-logo {
        font-family: 'Dancing Script', cursive;
        font-size: 30px;
        font-weight: 800;
        color: #fff;
        display: flex;
        align-items: center;
        gap: 10px;
        letter-spacing: 0.5px;
        background: rgba(255,255,255,0.04);
        padding: 8px 22px 8px 16px;
        border-radius: 12px;
        transition: background 0.18s;
        cursor: pointer;
    }
    .footer-logo-front {
        color: #fff;
        font-weight: 800;
        transition: color 0.18s;
    }
    .footer-logo-store {
        color: #febd69;
        font-weight: 700;
        margin-left: 2px;
        transition: color 0.18s;
    }
    .footer-logo:hover {
        background: rgba(255,255,255,0.10);
    }
    .footer-logo:hover .footer-logo-front {
        color: #fff;
        font-weight: 900;
    }
    .footer-logo:hover .footer-logo-store {
        color: #febd69;
    }
    .footer-logo i {
        color: #febd69;
        font-size: 28px;
    }
    .footer-brand-desc {
        font-family: 'Dancing Script', cursive;
        color: #febd69;
        font-size: 15px;
        font-weight: 500;
        margin-left: 2px;
    }
    .footer-links-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 32px;
    }
    .footer-links-col {
        display: flex;
        flex-direction: column;
        gap: 10px;
    }
    .footer-col-title {
        color: #febd69;
        font-weight: 700;
        font-size: 15px;
        margin-bottom: 8px;
        letter-spacing: .2px;
    }
    .footer-link {
        display: flex;
        align-items: center;
        gap: 10px;
        color: #e6edf3;
        text-decoration: none;
        font-size: 15px;
        padding: 10px 8px;
        border-radius: 12px;
        /* transition: background 0.18s, color 0.18s, transform 0.18s, font-weight 0.18s; */
        font-weight: 700;
    }
    .footer-link i {
        color: #febd69;
        font-size: 15px;
        transition: color 0.18s;
    }
    .footer-link:hover {
        background: rgba(255,255,255,0.10);
        border-radius: 5px;
        color: #fff;
    }
    .footer-link:hover i {
        color: #febd69;
    }
    .footer-socials {
        display: flex;
        gap: 14px;
        margin-top: 4px;
    }
    .footer-social {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 38px;
        height: 38px;
        border-radius: 50%;
        background: linear-gradient(135deg, #ff9900 0%, #febd69 100%);
        color: #232f3e;
        font-size: 18px;
        transition: background 0.18s, color 0.18s, transform 0.18s;
    }
    .footer-social:hover {
        background: linear-gradient(135deg, #febd69 0%, #ff9900 100%);
        color: #232f3e;
        transform: translateY(-2px) scale(1.08);
    }
    .footer-bottom-bar {
        max-width: 1200px;
        margin: 0 auto;
        padding: 18px 20px 18px 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        border-top: 1.5px solid rgba(255,255,255,0.08);
        font-size: 13px;
        color: #e6edf3;
    }
    .footer-bottom-links {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .footer-bottom-links a {
        color: #febd69;
        text-decoration: none;
        transition: color 0.18s;
    }
    .footer-bottom-links a:hover {
        color: #fff;
        text-decoration: underline;
    }
    @media (max-width: 700px) {
        .footer-main { padding: 24px 8px 10px 8px; gap: 18px; }
        .footer-links-grid { gap: 18px; }
        .footer-bottom-bar { flex-direction: column; gap: 8px; align-items: flex-start; }
    }
</style>
