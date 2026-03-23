@extends('layouts.seller')

@section('title', 'My Profile - Seller Central')

@section('extra_styles')
<style>
    .profile-container {
        max-width: 1000px;
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
    }

    .btn-edit {
        background: linear-gradient(135deg, #ff9900, #ffad33);
        color: #111;
        padding: 10px 25px;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-edit:hover {
        background: linear-gradient(135deg, #e88b00, #ff9900);
        transform: translateY(-1px);
    }

    .profile-card {
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

    .card-header i {
        font-size: 18px;
    }

    .card-body {
        padding: 25px;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 20px;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 5px;
    }

    .info-item.full-width {
        grid-column: span 2;
    }

    .info-label {
        font-size: 12px;
        color: #565959;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 600;
    }

    .info-value {
        font-size: 15px;
        color: #0f1111;
        padding: 10px 15px;
        background: #f7fafa;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
    }

    .info-value.empty {
        color: #888;
        font-style: italic;
    }

    .badge {
        display: inline-block;
        padding: 4px 10px;
        border-radius: 4px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .badge-success {
        background: #d4edda;
        color: #155724;
    }

    .badge-warning {
        background: #fff3cd;
        color: #856404;
    }

    .alert {
        padding: 15px 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .alert-success {
        background: #d4edda;
        color: #155724;
        border: 1px solid #c3e6cb;
    }

    .alert-warning {
        background: #fff3cd;
        color: #856404;
        border: 1px solid #ffeaa7;
    }

    .tax-status {
        display: flex;
        gap: 15px;
        flex-wrap: wrap;
        margin-top: 15px;
    }

    .tax-badge {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 500;
    }

    .tax-badge.verified {
        background: #e8f5e9;
        color: #2e7d32;
    }

    .tax-badge.pending {
        background: #fff8e1;
        color: #f57c00;
    }

    @media (max-width: 768px) {
        .info-grid {
            grid-template-columns: 1fr;
        }
        .info-item.full-width {
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
<div class="profile-container">
    
    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('error'))
        <div class="alert" style="background: #fdecea; border-left: 4px solid #c40000; padding: 15px 20px; margin-bottom: 20px; border-radius: 4px;">
            <i class="fas fa-exclamation-circle" style="color: #c40000;"></i>
            {{ session('error') }}
        </div>
    @endif

    {{-- Account Status Banner --}}
    @php $status = $seller->status ?? 'pending'; @endphp
    @if($status == 'pending')
        <div style="background: linear-gradient(135deg, #fff8e1, #fff3cd); border: 1px solid #ffc107; border-radius: 12px; padding: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
            <div style="background: #ffc107; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-clock" style="font-size: 24px; color: #856404;"></i>
            </div>
            <div>
                <h4 style="margin: 0 0 5px 0; color: #856404;">Account Pending Approval</h4>
                <p style="margin: 0; color: #856404; font-size: 14px;">Your account is under review. You can view your dashboard but cannot add or edit products until approved.</p>
            </div>
        </div>
    @elseif($status == 'banned')
        <div style="background: linear-gradient(135deg, #fdecea, #f8d7da); border: 1px solid #dc3545; border-radius: 12px; padding: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
            <div style="background: #dc3545; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-ban" style="font-size: 24px; color: #fff;"></i>
            </div>
            <div>
                <h4 style="margin: 0 0 5px 0; color: #721c24;">Account Suspended</h4>
                <p style="margin: 0; color: #721c24; font-size: 14px;">Your selling privileges have been revoked. Please contact support for assistance.</p>
            </div>
        </div>
    @else
        <div style="background: linear-gradient(135deg, #d4edda, #c3e6cb); border: 1px solid #28a745; border-radius: 12px; padding: 20px; margin-bottom: 25px; display: flex; align-items: center; gap: 15px;">
            <div style="background: #28a745; border-radius: 50%; width: 50px; height: 50px; display: flex; align-items: center; justify-content: center;">
                <i class="fas fa-check-circle" style="font-size: 24px; color: #fff;"></i>
            </div>
            <div>
                <h4 style="margin: 0 0 5px 0; color: #155724;">Account Active</h4>
                <p style="margin: 0; color: #155724; font-size: 14px;">Your account is verified and you have full access to all seller features.</p>
            </div>
        </div>
    @endif

    <div class="page-header">
        <h1><i class="fas fa-user-circle" style="color: #ff9900;"></i> My Profile</h1>
        <a href="{{ route('seller.profile.edit') }}" class="btn-edit">
            <i class="fas fa-edit"></i> Edit Profile
        </a>
    </div>

    <!-- Account Information -->
    <div class="profile-card">
        <div class="card-header">
            <i class="fas fa-user"></i> Account Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Full Name</span>
                    <div class="info-value">{{ $seller->name ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Phone Number</span>
                    <div class="info-value">{{ $seller->phone ?? 'N/A' }}</div>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Status</span>
                    <div class="info-value">
                        @if($status == 'active')
                            <span style="color: #28a745; font-weight: 600;"><i class="fas fa-check-circle"></i> Active</span>
                        @elseif($status == 'banned')
                            <span style="color: #dc3545; font-weight: 600;"><i class="fas fa-ban"></i> Suspended</span>
                        @else
                            <span style="color: #ffc107; font-weight: 600;"><i class="fas fa-clock"></i> Pending Approval</span>
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Email Address</span>
                    <div class="info-value">{{ $seller->email ?? 'N/A' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Business Information -->
    <div class="profile-card">
        <div class="card-header">
            <i class="fas fa-store"></i> Business Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item full-width">
                    <span class="info-label">Business / Shop Name</span>
                    <div class="info-value {{ !$seller->business_name ? 'empty' : '' }}">
                        {{ $seller->business_name ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item full-width">
                    <span class="info-label">Business Address</span>
                    <div class="info-value {{ !$seller->business_address ? 'empty' : '' }}">
                        {{ $seller->business_address ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">City</span>
                    <div class="info-value {{ !$seller->city ? 'empty' : '' }}">
                        {{ $seller->city ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">State</span>
                    <div class="info-value {{ !$seller->state ? 'empty' : '' }}">
                        {{ $seller->state ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Pincode</span>
                    <div class="info-value {{ !$seller->pincode ? 'empty' : '' }}">
                        {{ $seller->pincode ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Country</span>
                    <div class="info-value">{{ $seller->country ?? 'India' }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tax & Legal Information -->
    <div class="profile-card">
        <div class="card-header">
            <i class="fas fa-file-invoice"></i> Tax & Legal Information
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">GSTIN (GST Number)</span>
                    <div class="info-value {{ !$seller->gstin ? 'empty' : '' }}">
                        {{ $seller->gstin ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">PAN Number</span>
                    <div class="info-value {{ !$seller->pan ? 'empty' : '' }}">
                        {{ $seller->pan ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item full-width">
                    <span class="info-label">CIN (Company Identification Number)</span>
                    <div class="info-value {{ !$seller->cin ? 'empty' : '' }}">
                        {{ $seller->cin ?? 'Not provided' }}
                    </div>
                </div>
            </div>

            <div class="tax-status">
                @if($seller->gstin)
                    <div class="tax-badge verified">
                        <i class="fas fa-check-circle"></i> GST Registered
                    </div>
                @else
                    <div class="tax-badge pending">
                        <i class="fas fa-exclamation-circle"></i> GST Not Added
                    </div>
                @endif

                @if($seller->pan)
                    <div class="tax-badge verified">
                        <i class="fas fa-check-circle"></i> PAN Verified
                    </div>
                @else
                    <div class="tax-badge pending">
                        <i class="fas fa-exclamation-circle"></i> PAN Not Added
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Bank Details -->
    <div class="profile-card">
        <div class="card-header">
            <i class="fas fa-university"></i> Bank Details
        </div>
        <div class="card-body">
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">Bank Name</span>
                    <div class="info-value {{ !$seller->bank_name ? 'empty' : '' }}">
                        {{ $seller->bank_name ?? 'Not provided' }}
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">Account Number</span>
                    <div class="info-value {{ !$seller->bank_account ? 'empty' : '' }}">
                        @if($seller->bank_account)
                            {{ substr($seller->bank_account, 0, 4) }}****{{ substr($seller->bank_account, -4) }}
                        @else
                            Not provided
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <span class="info-label">IFSC Code</span>
                    <div class="info-value {{ !$seller->ifsc_code ? 'empty' : '' }}">
                        {{ $seller->ifsc_code ?? 'Not provided' }}
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
