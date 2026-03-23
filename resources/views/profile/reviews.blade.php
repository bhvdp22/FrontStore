<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>My Reviews - FrontStore</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

    /* Container */
    .container { max-width: 1100px; margin: 0 auto; padding: 30px 20px; }

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

    /* Stats Bar */
    .stats-bar {
      display: flex;
      gap: 20px;
      margin-bottom: 25px;
    }
    .stat-card {
      background: white;
      border-radius: 12px;
      padding: 20px 25px;
      flex: 1;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      display: flex;
      align-items: center;
      gap: 15px;
      transition: all 0.3s;
    }
    .stat-card:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    }
    .stat-icon {
      width: 50px;
      height: 50px;
      border-radius: 12px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
    }
    .stat-icon.orange { background: linear-gradient(135deg, #ff9900, #febd69); color: white; }
    .stat-icon.green { background: linear-gradient(135deg, #007600, #00a32a); color: white; }
    .stat-icon.blue { background: linear-gradient(135deg, #232f3e, #37475a); color: white; }
    .stat-content h3 { font-size: 24px; font-weight: 700; color: #0f1111; }
    .stat-content p { font-size: 13px; color: #565959; }

    /* Reviews Section */
    .reviews-section {
      background: white;
      border-radius: 16px;
      box-shadow: 0 4px 15px rgba(0,0,0,0.08);
      overflow: hidden;
    }
    .section-title {
      padding: 20px 25px;
      border-bottom: 1px solid #eee;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    .section-title h2 {
      font-size: 18px;
      font-weight: 600;
      color: #0f1111;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .section-title h2 i { color: #ff9900; }
    .review-count {
      background: #232f3e;
      color: white;
      padding: 5px 14px;
      border-radius: 20px;
      font-size: 13px;
      font-weight: 600;
    }

    /* Review Item */
    .review-item {
      padding: 25px;
      border-bottom: 1px solid #f0f0f0;
      transition: background 0.2s;
    }
    .review-item:last-child { border-bottom: none; }
    .review-item:hover { background: #fafafa; }

    .review-header {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      margin-bottom: 12px;
      gap: 15px;
    }
    .review-product {
      display: flex;
      align-items: center;
      gap: 12px;
      flex: 1;
    }
    .product-image {
      width: 60px;
      height: 60px;
      background: linear-gradient(135deg, #f8f9fa, #e9ecef);
      border-radius: 10px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #8d9096;
      font-size: 24px;
    }
    .product-details h4 {
      font-size: 15px;
      font-weight: 600;
      color: #0f1111;
      margin-bottom: 4px;
    }
    .product-details h4:hover { color: #c7511f; cursor: pointer; }

    /* Stars */
    .stars-row {
      display: flex;
      align-items: center;
      gap: 8px;
    }
    .stars {
      display: flex;
      gap: 2px;
    }
    .stars i { color: #ffa41c; font-size: 14px; }
    .stars i.empty { color: #ddd; }
    .rating-text { font-size: 13px; color: #565959; font-weight: 500; }

    .review-date {
      font-size: 12px;
      color: #565959;
      white-space: nowrap;
    }

    .review-title {
      font-size: 16px;
      font-weight: 600;
      color: #0f1111;
      margin-bottom: 8px;
    }
    .review-text {
      font-size: 14px;
      color: #333;
      line-height: 1.6;
      margin-bottom: 12px;
    }

    .review-meta {
      display: flex;
      align-items: center;
      gap: 15px;
      flex-wrap: wrap;
    }
    .verified-badge {
      display: inline-flex;
      align-items: center;
      gap: 5px;
      background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
      color: #2e7d32;
      padding: 5px 12px;
      border-radius: 20px;
      font-size: 12px;
      font-weight: 600;
    }
    .verified-badge i { font-size: 11px; }

    .review-actions {
      display: flex;
      gap: 10px;
    }
    .action-link {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      color: #007185;
      text-decoration: none;
      font-size: 13px;
      font-weight: 500;
      padding: 6px 12px;
      border-radius: 6px;
      transition: all 0.2s;
    }
    .action-link:hover {
      background: #e7f4f7;
      color: #005a70;
    }

    /* Empty State */
    .empty-state {
      text-align: center;
      padding: 60px 20px;
    }
    .empty-icon {
      width: 100px;
      height: 100px;
      background: linear-gradient(135deg, #f0f2f2, #e3e6e6);
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0 auto 25px;
    }
    .empty-icon i { font-size: 40px; color: #8d9096; }
    .empty-state h3 { 
      color: #0f1111; 
      font-size: 20px;
      margin-bottom: 10px; 
    }
    .empty-state p { 
      color: #565959; 
      margin-bottom: 25px;
      max-width: 400px;
      margin-left: auto;
      margin-right: auto;
    }
    .browse-btn {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
      color: #0f1111;
      text-decoration: none;
      padding: 14px 30px;
      border-radius: 25px;
      font-weight: 600;
      font-size: 14px;
      transition: all 0.2s;
      border: none;
      cursor: pointer;
    }
    .browse-btn:hover {
      background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
      box-shadow: 0 4px 12px rgba(247, 202, 0, 0.4);
      transform: translateY(-2px);
    }

    /* Pagination */
    .pagination-wrapper {
      padding: 20px 25px;
      border-top: 1px solid #eee;
      display: flex;
      justify-content: center;
    }

    @media (max-width: 768px) {
      .stats-bar { flex-direction: column; }
      .page-header { padding: 25px; }
      .page-header h1 { font-size: 22px; }
      .review-header { flex-direction: column; }
      .product-image { width: 50px; height: 50px; }
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
        <a href="{{ route('profile.index') }}">My Account</a>
        <span>/</span>
        <span style="color: #febd69;">My Reviews</span>
      </div>
      <h1><i class="fas fa-star"></i> My Reviews</h1>
      <p>View and manage all your product reviews in one place</p>
    </div>

    <!-- Stats Bar -->
    <div class="stats-bar">
      <div class="stat-card">
        <div class="stat-icon orange"><i class="fas fa-star"></i></div>
        <div class="stat-content">
          <h3>{{ $reviews->count() }}</h3>
          <p>Total Reviews</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-content">
          <h3>{{ $reviews->where('verified_purchase', true)->count() }}</h3>
          <p>Verified Purchases</p>
        </div>
      </div>
      <div class="stat-card">
        <div class="stat-icon blue"><i class="fas fa-chart-line"></i></div>
        <div class="stat-content">
          <h3>{{ $reviews->count() > 0 ? number_format($reviews->avg('rating'), 1) : '0.0' }}</h3>
          <p>Average Rating</p>
        </div>
      </div>
    </div>

    <!-- Reviews Section -->
    <div class="reviews-section">
      <div class="section-title">
        <h2><i class="fas fa-comments"></i> Your Reviews</h2>
        @if($reviews->count() > 0)
          <span class="review-count">{{ $reviews->count() }} {{ $reviews->count() === 1 ? 'Review' : 'Reviews' }}</span>
        @endif
      </div>

      @if($reviews->count() === 0)
        <div class="empty-state">
          <div class="empty-icon">
            <i class="fas fa-star-half-alt"></i>
          </div>
          <h3>No Reviews Yet</h3>
          <p>You haven't written any reviews yet. Share your thoughts on products you've purchased!</p>
          <a class="browse-btn" href="{{ route('shop.index') }}">
            <i class="fas fa-shopping-bag"></i> Browse Products
          </a>
        </div>
      @else
        @foreach($reviews as $review)
          <div class="review-item">
            <div class="review-header">
              <div class="review-product">
                <div class="product-image">
                  <i class="fas fa-box"></i>
                </div>
                <div class="product-details">
                  <h4 onclick="window.location.href='{{ route('shop.product', ['id' => $review->product_id]) }}'">
                    {{ $review->product->name ?? 'Product' }}
                  </h4>
                  <div class="stars-row">
                    <div class="stars">
                      @for($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $review->rating ? '' : 'empty' }}"></i>
                      @endfor
                    </div>
                    <span class="rating-text">{{ $review->rating }}.0 out of 5</span>
                  </div>
                </div>
              </div>
              <div class="review-date">
                <i class="far fa-calendar-alt"></i> {{ $review->created_at->format('M d, Y') }}
              </div>
            </div>

            <div class="review-title">{{ $review->title }}</div>
            <p class="review-text">{{ $review->review_text }}</p>

            <div class="review-meta">
              @if($review->verified_purchase)
                <span class="verified-badge">
                  <i class="fas fa-check-circle"></i> Verified Purchase
                </span>
              @endif
              <div class="review-actions">
                <a href="{{ route('shop.product', ['id' => $review->product_id]) }}" class="action-link">
                  <i class="fas fa-eye"></i> View Product
                </a>
              </div>
            </div>
          </div>
        @endforeach

        @if(method_exists($reviews, 'links'))
          <div class="pagination-wrapper">
            {{ $reviews->links() }}
          </div>
        @endif
      @endif
    </div>
  </div>

</body>
</html>
