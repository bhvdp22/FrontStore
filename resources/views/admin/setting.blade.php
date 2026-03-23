@extends('admin.layout')
@section('title', 'Platform Settings')
@section('header', 'Platform Settings')

@section('content')
    <h1 class="page-title">Settings</h1>
    <p class="page-subtitle">Configure commission, fees, and tax rules</p>

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        {{-- ── Commission & Fees ────────────────────── --}}
        <div class="card">
            <div class="card-title">Commission &amp; Fees</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="admin_commission">Admin Commission (%)</label>
                    <input type="number" step="0.01" id="admin_commission" name="admin_commission" class="form-control" value="{{ old('admin_commission', $settings->admin_commission) }}" required>
                </div>
                <div class="form-group">
                    <label for="platform_fee">Platform Fee (₹)</label>
                    <input type="number" step="0.01" id="platform_fee" name="platform_fee" class="form-control" value="{{ old('platform_fee', $settings->platform_fee) }}" required>
                </div>
            </div>
            <div class="form-group">
                <label for="platform_fee_label">Platform Fee Label</label>
                <input type="text" id="platform_fee_label" name="platform_fee_label" class="form-control" value="{{ old('platform_fee_label', $settings->platform_fee_label ?? 'Platform Fee') }}" required>
            </div>
            <div class="form-check">
                <input type="checkbox" id="show_platform_fee" name="show_platform_fee" {{ $settings->show_platform_fee ? 'checked' : '' }}>
                <label for="show_platform_fee">Show platform fee on invoices</label>
            </div>
        </div>

        {{-- ── Tax Settings ─────────────────────────── --}}
        <div class="card">
            <div class="card-title">Tax Configuration</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="gst_percentage">GST Rate (%)</label>
                    <input type="number" step="0.01" id="gst_percentage" name="gst_percentage" class="form-control" value="{{ old('gst_percentage', $settings->gst_percentage) }}" required>
                </div>
                <div class="form-group">
                    <label for="tax_label">Tax Label</label>
                    <input type="text" id="tax_label" name="tax_label" class="form-control" value="{{ old('tax_label', $settings->tax_label ?? 'GST') }}" required>
                </div>
            </div>
            <div class="form-check">
                <input type="checkbox" id="tax_included_in_price" name="tax_included_in_price" {{ $settings->tax_included_in_price ? 'checked' : '' }}>
                <label for="tax_included_in_price">Tax is included in product price</label>
            </div>
            <div class="form-check">
                <input type="checkbox" id="show_tax_on_invoice" name="show_tax_on_invoice" {{ $settings->show_tax_on_invoice ? 'checked' : '' }}>
                <label for="show_tax_on_invoice">Show tax breakdown on invoices</label>
            </div>
        </div>

        {{-- ── Business Identity ────────────────────── --}}
        <div class="card">
            <div class="card-title">Business Identity</div>
            <div class="form-row">
                <div class="form-group">
                    <label for="business_name">Business Name</label>
                    <input type="text" id="business_name" name="business_name" class="form-control" value="{{ old('business_name', $settings->business_name) }}">
                </div>
                <div class="form-group">
                    <label for="business_gstin">GSTIN</label>
                    <input type="text" id="business_gstin" name="business_gstin" class="form-control" value="{{ old('business_gstin', $settings->business_gstin) }}">
                </div>
            </div>
            <div class="form-group">
                <label for="business_address">Business Address</label>
                <textarea id="business_address" name="business_address" class="form-control" rows="3">{{ old('business_address', $settings->business_address) }}</textarea>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Settings</button>
    </form>
@endsection
