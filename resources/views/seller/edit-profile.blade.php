@extends('layouts.seller')

@section('title', 'Edit Profile - Seller Central')

@section('extra_styles')
<style>
    .edit-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .page-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 25px;
    }

    .page-header h1 {
        font-size: 24px;
        color: #0f1111;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .btn-back {
        background: #f5f5f5;
        color: #333;
        padding: 10px 20px;
        border: 1px solid #d5d9d9;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-back:hover {
        background: #e8e8e8;
    }

    .form-card {
        background: white;
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 20px;
        overflow: hidden;
    }

    .card-header {
        background: linear-gradient(135deg, #232f3e, #37475a);
        color: white;
        padding: 15px 25px;
        font-size: 16px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .card-body {
        padding: 25px;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-group label {
        font-size: 13px;
        color: #0f1111;
        font-weight: 600;
    }

    .form-group label .required {
        color: #c40000;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        padding: 10px 14px;
        border: 1px solid #a6a6a6;
        border-radius: 6px;
        font-size: 14px;
        transition: all 0.2s;
    }

    .form-group input:focus,
    .form-group select:focus,
    .form-group textarea:focus {
        outline: none;
        border-color: #ff9900;
        box-shadow: 0 0 0 3px rgba(255, 153, 0, 0.2);
    }

    .form-group textarea {
        min-height: 80px;
        resize: vertical;
    }

    .helper-text {
        font-size: 11px;
        color: #565959;
    }

    .error-text {
        color: #c40000;
        font-size: 12px;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .form-actions {
        display: flex;
        justify-content: flex-end;
        gap: 15px;
        margin-top: 30px;
        padding-top: 20px;
        border-top: 1px solid #e0e0e0;
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
        text-decoration: none;
    }

    .btn-secondary:hover {
        background: #e8e8e8;
    }

    .btn-primary {
        background: linear-gradient(135deg, #ff9900, #ffad33);
        color: #111;
    }

    .btn-primary:hover {
        background: linear-gradient(135deg, #e88b00, #ff9900);
        transform: translateY(-1px);
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-danger {
        background: #f8d7da;
        color: #721c24;
        border: 1px solid #f5c6cb;
    }

    .info-box {
        background: #e7f3ff;
        border: 1px solid #b8daff;
        border-radius: 8px;
        padding: 12px 15px;
        margin-bottom: 20px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        font-size: 13px;
        color: #004085;
    }

    .info-box i {
        font-size: 16px;
        margin-top: 2px;
    }

    @media (max-width: 768px) {
        .form-grid {
            grid-template-columns: 1fr;
        }
        .form-group.full-width {
            grid-column: span 1;
        }
        .page-header {
            flex-direction: column;
            gap: 15px;
            align-items: flex-start;
        }
    }
</style>
@endsection

@section('content')
<div class="edit-container">
    
    @if($errors->any())
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-circle"></i>
            Please fix the errors below and try again.
        </div>
    @endif

    <div class="page-header">
        <h1><i class="fas fa-edit" style="color: #ff9900;"></i> Edit Profile</h1>
        <a href="{{ route('seller.profile') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Back to Profile
        </a>
    </div>

    <form action="{{ route('seller.profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <!-- Account Information -->
        <div class="form-card">
            <div class="card-header">
                <i class="fas fa-user"></i> Account Information
            </div>
            <div class="card-body">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Full Name <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $seller->name) }}" required>
                        @error('name')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Phone Number <span class="required">*</span></label>
                        <input type="text" name="phone" value="{{ old('phone', $seller->phone) }}" required>
                        @error('phone')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label>Email Address <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $seller->email) }}" required>
                        @error('email')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Information -->
        <div class="form-card">
            <div class="card-header">
                <i class="fas fa-store"></i> Business Information
            </div>
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p style="margin: 0;">This information will appear on invoices sent to your customers.</p>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width">
                        <label>Business / Shop Name <span class="required">*</span></label>
                        <input type="text" name="business_name" value="{{ old('business_name', $seller->business_name) }}" placeholder="e.g., Aaru Innovative" required>
                        @error('business_name')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label>Business Address <span class="required">*</span></label>
                        <textarea name="business_address" placeholder="Full business address" required>{{ old('business_address', $seller->business_address) }}</textarea>
                        @error('business_address')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>City <span class="required">*</span></label>
                        <input type="text" name="city" value="{{ old('city', $seller->city) }}" placeholder="e.g., Surat" required>
                        @error('city')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>State <span class="required">*</span></label>
                        <input type="text" name="state" value="{{ old('state', $seller->state) }}" placeholder="e.g., Gujarat" required>
                        @error('state')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Pincode <span class="required">*</span></label>
                        <input type="text" name="pincode" value="{{ old('pincode', $seller->pincode) }}" placeholder="e.g., 395006" maxlength="6" required>
                        @error('pincode')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Country</label>
                        <select name="country">
                            <option value="India" {{ old('country', $seller->country) == 'India' ? 'selected' : '' }}>🇮🇳 India</option>
                            <option value="USA" {{ old('country', $seller->country) == 'USA' ? 'selected' : '' }}>🇺🇸 USA</option>
                            <option value="UK" {{ old('country', $seller->country) == 'UK' ? 'selected' : '' }}>🇬🇧 UK</option>
                            <option value="UAE" {{ old('country', $seller->country) == 'UAE' ? 'selected' : '' }}>🇦🇪 UAE</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tax & Legal Information -->
        <div class="form-card">
            <div class="card-header">
                <i class="fas fa-file-invoice"></i> Tax & Legal Information
            </div>
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-info-circle"></i>
                    <p style="margin: 0;">These details are optional but recommended for tax compliance. They will appear on your invoices.</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>GSTIN (GST Number)</label>
                        <input type="text" name="gstin" value="{{ old('gstin', $seller->gstin) }}" placeholder="e.g., 24BHFPR1987N1ZN" maxlength="15" style="text-transform: uppercase;">
                        <div class="helper-text">15-character GST Identification Number</div>
                        @error('gstin')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>PAN Number</label>
                        <input type="text" name="pan" value="{{ old('pan', $seller->pan) }}" placeholder="e.g., BHFPR1987N" maxlength="10" style="text-transform: uppercase;">
                        <div class="helper-text">10-character Permanent Account Number</div>
                        @error('pan')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group full-width">
                        <label>CIN (Company Identification Number)</label>
                        <input type="text" name="cin" value="{{ old('cin', $seller->cin) }}" placeholder="e.g., U74999GJ2024PTC123456" maxlength="21" style="text-transform: uppercase;">
                        <div class="helper-text">Required only for registered Private Limited companies</div>
                        @error('cin')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Bank Details -->
        <div class="form-card">
            <div class="card-header">
                <i class="fas fa-university"></i> Bank Details
            </div>
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-lock"></i>
                    <p style="margin: 0;">Your bank details are securely stored and used for payment disbursements.</p>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label>Bank Name</label>
                        <input type="text" name="bank_name" value="{{ old('bank_name', $seller->bank_name) }}" placeholder="e.g., State Bank of India">
                        @error('bank_name')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>Account Number</label>
                        <input type="text" name="bank_account" value="{{ old('bank_account', $seller->bank_account) }}" placeholder="e.g., 1234567890123">
                        @error('bank_account')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>IFSC Code</label>
                        <input type="text" name="ifsc_code" value="{{ old('ifsc_code', $seller->ifsc_code) }}" placeholder="e.g., SBIN0001234" style="text-transform: uppercase;">
                        @error('ifsc_code')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Storefront Settings -->
        <div class="form-card">
            <div class="card-header">
                <i class="fas fa-store-alt"></i> Storefront Settings
            </div>
            <div class="card-body">
                <div class="info-box">
                    <i class="fas fa-globe"></i>
                    <p style="margin: 0;">Enable your public storefront so customers can browse your brand page directly. Your storefront URL will be: <strong>/seller/{{ $seller->slug ?? 'your-business-name' }}</strong></p>
                </div>

                <div class="form-grid">
                    <div class="form-group full-width" style="flex-direction: row; align-items: center; gap: 12px;">
                        <input type="hidden" name="storefront_enabled" value="0">
                        <input type="checkbox" name="storefront_enabled" value="1" id="storefront_toggle" {{ old('storefront_enabled', $seller->storefront_enabled) ? 'checked' : '' }} style="width: 18px; height: 18px; cursor: pointer;">
                        <label for="storefront_toggle" style="cursor: pointer; margin: 0;">Enable Public Storefront</label>
                    </div>

                    <div class="form-group full-width">
                        <label>Brand Story / About</label>
                        <textarea name="brand_story" placeholder="Tell customers about your brand, your values, and what makes you unique..." rows="5">{{ old('brand_story', $seller->brand_story) }}</textarea>
                        <div class="helper-text">Max 5000 characters. This is displayed on your public storefront.</div>
                        @error('brand_story')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Banner Image</label>
                        @if($seller->banner_image)
                            <div style="margin-bottom: 8px;">
                                <img src="{{ $seller->banner_image }}" alt="Current banner" style="max-width: 100%; max-height: 120px; border-radius: 6px; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="banner_image" accept="image/*">
                        <div class="helper-text">Recommended: 1200×300px, max 2 MB</div>
                        @error('banner_image')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Store Logo</label>
                        @if($seller->logo)
                            <div style="margin-bottom: 8px;">
                                <img src="{{ $seller->logo }}" alt="Current logo" style="max-width: 80px; max-height: 80px; border-radius: 50%; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" name="logo" accept="image/*">
                        <div class="helper-text">Recommended: 200×200px, max 1 MB</div>
                        @error('logo')
                            <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div style="margin-top: 20px;">
                    <label style="font-size: 14px; font-weight: 600; color: #0f1111; display: block; margin-bottom: 10px;">Social Links</label>
                    <div class="form-grid">
                        <div class="form-group">
                            <label><i class="fas fa-globe" style="color: #0066c0; margin-right: 5px;"></i> Website</label>
                            <input type="url" name="social_links[website]" value="{{ old('social_links.website', $seller->social_links['website'] ?? '') }}" placeholder="https://yourbrand.com">
                            @error('social_links.website')
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label><i class="fab fa-instagram" style="color: #e1306c; margin-right: 5px;"></i> Instagram</label>
                            <input type="text" name="social_links[instagram]" value="{{ old('social_links.instagram', $seller->social_links['instagram'] ?? '') }}" placeholder="@yourbrand">
                            @error('social_links.instagram')
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label><i class="fab fa-facebook" style="color: #1877f2; margin-right: 5px;"></i> Facebook</label>
                            <input type="text" name="social_links[facebook]" value="{{ old('social_links.facebook', $seller->social_links['facebook'] ?? '') }}" placeholder="facebook.com/yourbrand">
                            @error('social_links.facebook')
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label><i class="fab fa-twitter" style="color: #1da1f2; margin-right: 5px;"></i> Twitter / X</label>
                            <input type="text" name="social_links[twitter]" value="{{ old('social_links.twitter', $seller->social_links['twitter'] ?? '') }}" placeholder="@yourbrand">
                            @error('social_links.twitter')
                                <div class="error-text"><i class="fas fa-exclamation-circle"></i> {{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Actions -->
        <div class="form-actions">
            <a href="{{ route('seller.profile') }}" class="btn btn-secondary">
                <i class="fas fa-times"></i> Cancel
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Changes
            </button>
        </div>
    </form>

</div>
@endsection
