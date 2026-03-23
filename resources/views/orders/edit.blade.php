@extends('layouts.seller')

@section('title', 'Edit Order - Seller Central')

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
        box-sizing: border-box; 
    }
    
    /* Read-only inputs look different so user knows they can't change them */
    input[readonly] {
        background-color: #f0f2f2;
        color: #565959;
        border-color: #d5d9d9;
        cursor: not-allowed;
    }

    input:focus, select:focus { border-color: #e77600; box-shadow: 0 0 3px 2px rgba(228,121,17, 0.5); outline: none; }

    /* Grid */
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
    
    .order-info-badge {
        display: inline-block;
        background: #f0f2f2;
        padding: 6px 12px;
        border-radius: 4px;
        font-size: 12px;
        font-weight: 600;
        color: #0f1111;
        margin-right: 10px;
    }

    /* Error Messages */
    .error-box { background: #fff4f4; border: 1px solid #c40000; color: #c40000; padding: 15px; margin-bottom: 20px; border-radius: 8px; font-size: 13px; }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Order Details</h1>
            <p class="page-subtitle">
                <span class="order-info-badge">Order ID: {{ $order->order_id }}</span>
                <span class="order-info-badge">Seller Order ID: #{{ $order->id }}</span>
            </p>
            <p class="page-subtitle">Update shipping status or customer contact details. Product details are locked to preserve records.</p>
        </div>
        <a href="{{ route('orders.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Orders
        </a>
    </div>

    <div class="form-card">
    @if ($errors->any())
        <div class="error-box">
            <strong>Please correct the following errors:</strong>
            <ul style="margin: 5px 0 0 20px; padding: 0;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('orders.update', $order->id) }}">
      @csrf
      @method('PUT')

      <div class="form-group">
        <label>Order ID (Locked)</label>
        <input type="text" value="{{ $order->order_id }}" readonly>
      </div>

      <div class="row">
        <div class="form-group" style="flex:1">
          <label>Customer Name</label>
          <input type="text" name="customer_name" value="{{ old('customer_name', $order->customer_name) }}" required>
        </div>
        <div class="form-group" style="flex:1">
          <label>Customer Email</label>
          <input type="email" name="customer_email" value="{{ old('customer_email', $order->customer_email) }}">
        </div>
      </div>

      <div class="row">
        <div class="form-group" style="flex:2">
          <label>Product Name</label>
          <input type="text" value="{{ $order->product_name }}" readonly>
        </div>
        <div class="form-group" style="flex:1">
          <label>SKU</label>
          <input type="text" value="{{ $order->sku }}" readonly>
        </div>
      </div>

      <div class="row">
        <div class="form-group" style="flex:1">
            <label>Quantity Sold</label>
            <input type="text" value="{{ $order->quantity }}" readonly>
        </div>
        <div class="form-group" style="flex:1">
            <label>Total Price Paid (₹)</label>
            <input type="text" value="{{ number_format($order->total_price, 2) }}" readonly>
        </div>
      </div>

      <div class="form-group">
        <label>Order Status</label>
        <select name="status" style="background-color: #fff;">
            @php $s = old('status', $order->status); @endphp
            <option value="Unshipped" {{ $s==='Unshipped' ? 'selected' : '' }}>Unshipped</option>
            <option value="Shipped" {{ $s==='Shipped' ? 'selected' : '' }}>Shipped</option>
            <option value="Delivered" {{ $s==='Delivered' ? 'selected' : '' }}>Delivered</option>
            <option value="Cancelled" {{ $s==='Cancelled' ? 'selected' : '' }}>Cancelled</option>
        </select>
      </div>

      <div style="margin-top: 10px;">
        <button class="btn-submit" type="submit">Update Order</button>
        <a class="btn-cancel" href="{{ route('orders.index') }}">Cancel</a>
      </div>

    </form>
    </div>
</div>
@endsection