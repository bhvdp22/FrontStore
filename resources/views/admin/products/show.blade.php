@extends('admin.layout')
@section('title', 'Product Details')
@section('header', 'Product Details')

@section('content')
    <a href="{{ route('admin.products') }}" class="back-link">Back to products</a>

    <h1 class="page-title">{{ $product->name }}</h1>
    <p class="page-subtitle">SKU: {{ $product->sku }} — <span class="status-text {{ $product->status }}">{{ $product->status }}</span></p>

    <div class="grid-2">
        <div class="card">
            <div class="card-title">Product Information</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $product->name }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">SKU</div>
                    <div class="dd">{{ $product->sku }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">ASIN</div>
                    <div class="dd">{{ $product->asin ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Price</div>
                    <div class="dd"><span class="rupee">₹</span>{{ number_format($product->price, 2) }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Stock</div>
                    <div class="dd">{{ $product->quantity }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Sponsored</div>
                    <div class="dd">{{ $product->is_sponsored ? 'Yes' : 'No' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Category</div>
                    <div class="dd">{{ $product->category->name ?? '—' }}</div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-title">Seller</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="dt">Name</div>
                    <div class="dd">{{ $product->seller->name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Business</div>
                    <div class="dd">{{ $product->seller->business_name ?? '—' }}</div>
                </div>
                <div class="detail-item">
                    <div class="dt">Status</div>
                    <div class="dd"><span class="status-text {{ $product->seller->status ?? '' }}">{{ $product->seller->status ?? '—' }}</span></div>
                </div>
            </div>
        </div>
    </div>

    @if($product->description)
    <div class="card">
        <div class="card-title">Description</div>
        <p style="font-size: 13.5px; color: var(--gray-600); line-height: 1.7;">{{ $product->description }}</p>
    </div>
    @endif

    {{-- Reviews --}}
    <div class="table-wrap">
        <div class="table-header">
            <h2>Reviews ({{ $product->reviews->count() }})</h2>
        </div>
        <table>
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Rating</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                @forelse($product->reviews as $review)
                    <tr>
                        <td>{{ $review->customer_name }}</td>
                        <td>
                            <span class="stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <span class="{{ $i <= $review->rating ? 'filled' : '' }}">★</span>
                                @endfor
                            </span>
                        </td>
                        <td>{{ $review->title ?? '—' }}</td>
                        <td><span class="status-text {{ $review->status }}">{{ $review->status }}</span></td>
                        <td>{{ $review->created_at->format('d M Y') }}</td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="empty-state">No reviews yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
