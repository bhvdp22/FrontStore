@extends('layouts.seller')

@section('title', 'Create Order - Seller Central')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f1f3f3; }

    /* Container */
    .container { max-width: 800px; margin: 30px auto; padding: 0; }
    
    /* Header */
    h2 { margin-top: 0; color: #111; font-weight: 700; }
    p.subtitle { color: #565959; font-size: 13px; margin-bottom: 20px; }

    /* Form Elements */
    .form-group { margin-bottom: 20px; }
    label { display: block; font-weight: 700; margin-bottom: 6px; font-size: 13px; color: #111; }
    
    input, select { 
        width: 100%; 
        padding: 10px; 
        border: 1px solid #888; 
        border-radius: 4px; 
        font-size: 14px; 
        box-sizing: border-box; /* Important for padding */
    }
    
    input:focus, select:focus { border-color: #e77600; box-shadow: 0 0 3px 2px rgba(228,121,17, 0.5); outline: none; }

    /* Grid for side-by-side inputs */
    .row { display: flex; gap: 20px; }
    
    /* Buttons */
    .btn-submit { 
        background: #f0c14b; 
        border: 1px solid #a88734; 
        color: #111; 
        padding: 10px 20px; 
        border-radius: 3px; 
        cursor: pointer; 
        font-weight: bold; 
        font-size: 13px;
    }
    .btn-submit:hover { background: #f4d078; }

    .btn-cancel { 
        text-decoration: none; 
        color: #007185; 
        margin-left: 15px; 
        font-size: 13px; 
    }
    .btn-cancel:hover { text-decoration: underline; color: #c7511f; }

    /* Card Design */
    .form-card {
        background: white;
        border-radius: 8px;
        padding: 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        margin-bottom: 20px;
    }
    
    .page-header {
        background: white;
        border-radius: 8px;
        padding: 20px 30px;
        box-shadow: 0 2px 4px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .page-title {
        font-size: 24px;
        font-weight: 700;
        color: #0f1111;
        margin: 0;
    }
    
    .page-subtitle {
        font-size: 13px;
        color: #565959;
        margin: 8px 0 0 0;
    }

    /* Error Messages */
    .error-box { background: #fff4f4; border: 1px solid #c40000; color: #c40000; padding: 15px; margin-bottom: 20px; border-radius: 8px; font-size: 13px; }
    .text-danger { color: #c40000; font-size: 12px; margin-top: 4px; }
    
    .success-message {
        background: #f0fdf4;
        border: 1px solid #10b981;
        color: #065f46;
        padding: 15px;
        margin-bottom: 20px;
        border-radius: 8px;
        font-size: 13px;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Create New Order</h1>
            <p class="page-subtitle">Enter customer details and select a product. Total price will be calculated automatically.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="form-card">
    @if ($errors->any())
        <div class="error-box">
            <strong>There were problems with your input:</strong>
            <ul style="margin: 5px 0 0 20px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.store') }}">
      @csrf

      <div class="row">
        <div class="form-group" style="flex:1">
          <label>Customer Name <span style="color:#c40000">*</span></label>
          <input type="text" name="customer_name" value="{{ old('customer_name') }}" required placeholder="e.g. Rahul Sharma">
        </div>
        <div class="form-group" style="flex:1">
          <label>Customer Email (Optional)</label>
          <input type="email" name="customer_email" value="{{ old('customer_email') }}" placeholder="e.g. rahul@example.com">
        </div>
      </div>

      <div class="form-group">
        <label>Select Product <span style="color:#c40000">*</span></label>
        <select name="sku" required>
            <option value="">-- Choose a Product --</option>
            @foreach($products as $product)
                <option value="{{ $product->sku }}" {{ old('sku') == $product->sku ? 'selected' : '' }}>
                    {{ $product->name }} (Price: ₹{{ number_format($product->price) }} | Stock: {{ $product->quantity }})
                </option>
            @endforeach
        </select>
        <small style="color: #565959;">Price and Product Details will be auto-filled based on selection.</small>
      </div>

      <div class="row">
        <div class="form-group" style="flex:1">
          <label>Quantity <span style="color:#c40000">*</span></label>
          <input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}" required>
        </div>
        
        <div class="form-group" style="flex:1">
          <label>Order Status</label>
          <select name="status">
            <option value="Unshipped">Unshipped</option>
            <option value="Shipped">Shipped</option>
            <option value="Delivered">Delivered</option>
          </select>
        </div>
      </div>

      <div class="form-group">
        <label>Payment Method <span style="color:#c40000">*</span></label>
        <select name="payment_method" id="payment_method" required>
            <option value="">-- Select Payment Method --</option>
            <option value="Cash on Delivery" {{ old('payment_method') == 'Cash on Delivery' ? 'selected' : '' }}>Cash on Delivery</option>
            <option value="Credit Card" {{ old('payment_method') == 'Credit Card' ? 'selected' : '' }}>Credit Card</option>
            <option value="Debit Card" {{ old('payment_method') == 'Debit Card' ? 'selected' : '' }}>Debit Card</option>
            <option value="UPI" {{ old('payment_method') == 'UPI' ? 'selected' : '' }}>UPI</option>
            <option value="Net Banking" {{ old('payment_method') == 'Net Banking' ? 'selected' : '' }}>Net Banking</option>
            <option value="Cash" {{ old('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
        </select>
      </div>

      <div class="form-group" id="transaction_field" style="display: none;">
        <label>Transaction/Reference ID</label>
        <input type="text" name="transaction_id" value="{{ old('transaction_id') }}" placeholder="Enter transaction ID (optional)">
        <small style="color: #565959;">Enter if payment already completed</small>
      </div>

      <div class="form-group">
        <label>Payment Status <span style="color:#c40000">*</span></label>
        <select name="payment_status" required>
            <option value="Pending" {{ old('payment_status') == 'Pending' ? 'selected' : '' }}>Pending</option>
            <option value="Completed" {{ old('payment_status') == 'Completed' ? 'selected' : '' }}>Completed</option>
        </select>
        <small style="color: #565959;">Mark as 'Completed' if payment already received</small>
      </div>

      <script>
        document.getElementById('payment_method').addEventListener('change', function() {
            const transactionField = document.getElementById('transaction_field');
            if (this.value && this.value !== 'Cash on Delivery' && this.value !== 'Cash') {
                transactionField.style.display = 'block';
            } else {
                transactionField.style.display = 'none';
            }
        });
        
        // Trigger on page load if old value exists
        if (document.getElementById('payment_method').value && 
            document.getElementById('payment_method').value !== 'Cash on Delivery' && 
            document.getElementById('payment_method').value !== 'Cash') {
            document.getElementById('transaction_field').style.display = 'block';
        }
      </script>

      <div style="margin-top: 10px;">
        <button class="btn-submit" type="submit">Place Order</button>
        <a class="btn-cancel" href="{{ route('orders.index') }}">Cancel</a>
      </div>

    </form>
    </div>
</div>
@endsection