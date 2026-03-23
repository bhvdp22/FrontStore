<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leave a Product Review</title>
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

        body {
            background-color: #f1f1f1;
            padding: 20px;
        }

        .container {
            max-width: 700px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 4px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header {
            margin-bottom: 30px;
        }

        .header h1 {
            color: #002e36;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .product-info {
            display: flex;
            gap: 15px;
            padding: 15px;
            background-color: #f7fafa;
            border-radius: 4px;
            margin-bottom: 20px;
        }

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            background: white;
            padding: 5px;
            border-radius: 2px;
        }

        .product-details {
            flex: 1;
        }

        .product-details h3 {
            color: #002e36;
            font-size: 16px;
            margin-bottom: 5px;
        }

        .product-details p {
            color: #555;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 10px;
            border: 1px solid #d5d9d9;
            border-radius: 4px;
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #fff;
        }

        textarea {
            resize: vertical;
            min-height: 120px;
        }

        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus,
        select:focus {
            outline: none;
            border-color: #ff9900;
            box-shadow: 0 0 0 2px rgba(255, 153, 0, 0.1);
        }

        .rating-group {
            display: flex;
            gap: 10px;
            align-items: center;
        }

        .star {
            font-size: 32px;
            color: #ddd;
            cursor: pointer;
            transition: color 0.2s ease;
            border: none;
            background: transparent;
            padding: 0;
            line-height: 1;
        }

        .star:hover,
        .star.active {
            color: #ff9900;
        }

        .rating-value {
            margin-left: 10px;
            color: #555;
            font-size: 14px;
            min-width: 80px;
        }

        .form-section {
            margin-bottom: 25px;
        }

        .form-section h3 {
            color: #002e36;
            font-size: 16px;
            margin-bottom: 15px;
            border-bottom: 2px solid #ff9900;
            padding-bottom: 10px;
        }

        .button-group {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        button {
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-submit {
            background-color: #ff9900;
            color: white;
        }

        .btn-submit:hover {
            background-color: #e68a00;
        }

        .btn-cancel {
            background-color: #f0f0f0;
            color: #333;
            border: 1px solid #d5d9d9;
        }

        .btn-cancel:hover {
            background-color: #e0e0e0;
        }

        .success-message {
            padding: 12px 15px;
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
        }

        .error-message {
            padding: 12px 15px;
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            border-radius: 4px;
            margin-bottom: 20px;
            display: none;
        }

        .terms {
            font-size: 12px;
            color: #666;
            margin-top: 15px;
        }

        @media (max-width: 600px) {
            .container {
                padding: 20px;
            }

            .product-info {
                flex-direction: column;
            }

            .button-group {
                flex-direction: column;
            }

            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Write a Product Review</h1>
            <p style="color: #666; font-size: 14px;">Share your experience with this product to help other customers</p>
        </div>

        <!-- Product Information -->
        <div class="product-info">
            <img src="{{ $product->img_path }}" alt="{{ $product->name }}" class="product-image">
            <div class="product-details">
                <h3>{{ $product->name }}</h3>
                <p>Price: <strong>₹{{ number_format($product->price, 2) }}</strong></p>
                <p>SKU: {{ $product->sku }}</p>
            </div>
        </div>

        <!-- Messages -->
        <div class="success-message" id="successMessage">
            ✓ Review submitted successfully! Thank you for your feedback.
        </div>
        <div class="error-message" id="errorMessage"></div>

        <!-- Review Form (no name/email; linked to profile) -->
        <form id="reviewForm">
            @csrf
            <!-- Rating Section -->
            <div class="form-section">
                <h3>Rating</h3>
                <div class="form-group">
                    <label>Your rating *</label>
                    <div class="rating-group" id="reviewFormStars">
                        <button type="button" class="star" data-rating="1" aria-label="1 star">☆</button>
                        <button type="button" class="star" data-rating="2" aria-label="2 stars">☆</button>
                        <button type="button" class="star" data-rating="3" aria-label="3 stars">☆</button>
                        <button type="button" class="star" data-rating="4" aria-label="4 stars">☆</button>
                        <button type="button" class="star" data-rating="5" aria-label="5 stars">☆</button>
                        <span class="rating-value" id="ratingValue">5/5</span>
                    </div>
                </div>
            </div>

            <!-- Review Section -->
            <div class="form-section">
                <h3>Your Review</h3>
                <div class="form-group">
                    <label>Write your review *</label>
                    <textarea name="review_text" placeholder="Share your experience with this product." required></textarea>
                </div>
            </div>

            <!-- Hidden Fields -->
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            @if(isset($order_id))
                <input type="hidden" name="order_id" value="{{ $order_id }}">
            @endif
            <!-- Selected rating -->
            <input type="hidden" id="ratingInput" name="rating" value="5">

            <!-- Terms & Submit -->
            <div class="terms">
                <input type="checkbox" id="agreeTerms" required>
                <label for="agreeTerms" style="display: inline; font-weight: normal;">I confirm this is my genuine experience with this product</label>
            </div>

            <div class="button-group">
                <button type="button" class="btn-cancel" onclick="window.history.back()">Cancel</button>
                <button type="submit" class="btn-submit">Submit Review</button>
            </div>
        </form>
    </div>

    <script>
        const ratingInput = document.getElementById('ratingInput');
        const ratingValue = document.getElementById('ratingValue');
        const starButtons = document.querySelectorAll('#reviewFormStars .star');

        function paintReviewFormStars(value) {
            starButtons.forEach((starBtn) => {
                const starValue = parseInt(starBtn.dataset.rating || '0', 10);
                starBtn.textContent = starValue <= value ? '★' : '☆';
            });
            if (ratingValue) {
                ratingValue.textContent = `${value}/5`;
            }
        }

        starButtons.forEach((starBtn) => {
            starBtn.addEventListener('click', function() {
                const value = parseInt(this.dataset.rating || '5', 10);
                ratingInput.value = value;
                paintReviewFormStars(value);
            });
        });

        paintReviewFormStars(parseInt(ratingInput.value || '5', 10));

        // Form submission
        document.getElementById('reviewForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('{{ route("reviews.store") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('successMessage').style.display = 'block';
                    setTimeout(() => {
                        window.history.back();
                    }, 2000);
                } else {
                    showError(data.message || 'Error submitting review');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showError('Error submitting review. Please try again.');
            });
        });

        function showError(message) {
            const errorDiv = document.getElementById('errorMessage');
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    </script>
</body>
</html>
