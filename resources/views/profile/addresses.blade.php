<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Addresses - FrontStore</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,400&family=Montserrat:wght@500;600;700;800;900&family=Dancing+Script:wght@400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Poppins', sans-serif; background: linear-gradient(135deg, #f5f7fa 0%, #e4e8ec 100%); min-height: 100vh; }

        .container { max-width: 1200px; margin: 0 auto; padding: 30px 20px; }

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

        /* Alert */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            font-weight: 500;
        }
        .alert-success {
            background: linear-gradient(135deg, #dcfce7, #bbf7d0);
            color: #166534;
            border: 1px solid #86efac;
        }
        .alert-error {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border: 1px solid #fca5a5;
        }

        /* Add Address Button */
        .add-address-card {
            background: white;
            border: 2px dashed #d5d9d9;
            border-radius: 16px;
            padding: 50px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        .add-address-card:hover {
            border-color: #ff9900;
            background: linear-gradient(135deg, #fff9f0, #fff);
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
        }
        .add-address-card i {
            font-size: 48px;
            color: #ff9900;
            margin-bottom: 15px;
        }
        .add-address-card p {
            color: #0f1111;
            font-size: 16px;
            font-weight: 600;
        }

        /* Address Grid */
        .address-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(320px, 1fr));
            gap: 20px;
        }

        /* Address Card */
        .address-card {
            background: white;
            border: 1px solid #e0e0e0;
            border-radius: 16px;
            padding: 25px;
            position: relative;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            transition: all 0.3s;
        }
        .address-card:hover {
            box-shadow: 0 8px 25px rgba(0,0,0,0.12);
            transform: translateY(-3px);
        }
        .address-card.default {
            border: 2px solid #007185;
        }
        .default-badge {
            position: absolute;
            top: -1px;
            right: 20px;
            background: linear-gradient(135deg, #007185, #00a0b0);
            color: white;
            padding: 6px 14px;
            border-radius: 0 0 8px 8px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .address-type {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: linear-gradient(135deg, #f0f2f2, #e8e8e8);
            color: #565959;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 15px;
        }
        .address-type.home { background: linear-gradient(135deg, #dbeafe, #bfdbfe); color: #1e40af; }
        .address-type.work { background: linear-gradient(135deg, #fef3c7, #fde68a); color: #92400e; }
        .address-type.other { background: linear-gradient(135deg, #e0e7ff, #c7d2fe); color: #3730a3; }
        
        .address-name {
            font-size: 16px;
            font-weight: 700;
            color: #0f1111;
            margin-bottom: 8px;
        }
        .address-text {
            font-size: 14px;
            color: #565959;
            line-height: 1.6;
            margin-bottom: 4px;
        }
        .address-phone {
            font-size: 14px;
            color: #0f1111;
            margin-top: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .address-phone i { color: #007185; }
        .address-actions {
            margin-top: 20px;
            padding-top: 15px;
            border-top: 1px solid #eee;
            display: flex;
            gap: 20px;
        }
        .address-actions a {
            color: #007185;
            font-size: 13px;
            text-decoration: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 6px;
            font-weight: 500;
        }
        .address-actions a:hover {
            color: #c7511f;
        }
        .address-actions a.delete { color: #c7511f; }
        .address-actions a.delete:hover { color: #a31f1f; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0,0,0,0.6);
            z-index: 1000;
            justify-content: center;
            align-items: center;
            backdrop-filter: blur(4px);
        }
        .modal-overlay.active { display: flex; }
        .modal {
            background: white;
            border-radius: 16px;
            width: 100%;
            max-width: 550px;
            max-height: 90vh;
            overflow-y: auto;
            margin: 20px;
            box-shadow: 0 25px 50px rgba(0,0,0,0.25);
        }
        .modal-header {
            padding: 20px 25px;
            border-bottom: 1px solid #eee;
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, #f8f9fa, #fff);
        }
        .modal-header h3 {
            font-size: 20px;
            font-weight: 700;
            color: #0f1111;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .modal-header h3 i { color: #ff9900; }
        .modal-close {
            background: none;
            border: none;
            font-size: 28px;
            color: #565959;
            cursor: pointer;
            transition: color 0.2s;
        }
        .modal-close:hover { color: #c7511f; }
        .modal-body { padding: 25px; }

        /* Form */
        .form-group {
            margin-bottom: 18px;
        }
        .form-group label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #0f1111;
            margin-bottom: 6px;
        }
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 14px;
            border: 1px solid #d5d9d9;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007185;
            box-shadow: 0 0 0 3px rgba(0,113,133,0.1);
        }
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 12px;
            background: #f7fafa;
            border-radius: 8px;
            border: 1px solid #d5d9d9;
        }
        .form-check input { width: auto; }
        .form-check label { margin-bottom: 0; font-weight: 500; }

        /* Buttons */
        .btn {
            padding: 12px 24px;
            border-radius: 25px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .btn-primary {
            background: linear-gradient(180deg, #ffd814 0%, #f7ca00 100%);
            color: #0f1111;
        }
        .btn-primary:hover {
            background: linear-gradient(180deg, #f7ca00 0%, #e6b800 100%);
            box-shadow: 0 4px 12px rgba(247, 202, 0, 0.4);
            transform: translateY(-2px);
        }
        .btn-secondary {
            background: white;
            color: #0f1111;
            border: 1px solid #d5d9d9;
        }
        .btn-secondary:hover {
            background: #f7fafa;
            border-color: #007185;
            color: #007185;
        }
        .modal-footer {
            padding: 20px 25px;
            border-top: 1px solid #eee;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            background: linear-gradient(135deg, #f8f9fa, #fff);
        }

        @media (max-width: 768px) {
            .stats-bar { flex-direction: column; }
            .page-header { padding: 25px; }
            .page-header h1 { font-size: 22px; }
            .form-row { grid-template-columns: 1fr; }
            .address-grid { grid-template-columns: 1fr; }
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
                <span style="color: #febd69;">My Addresses</span>
            </div>
            <h1><i class="fas fa-map-marker-alt"></i> My Addresses</h1>
            <p>Manage your delivery addresses for faster checkout</p>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        <!-- Stats Bar -->
        <div class="stats-bar">
            <div class="stat-card">
                <div class="stat-icon orange"><i class="fas fa-map-marker-alt"></i></div>
                <div class="stat-content">
                    <h3>{{ count($addresses) }}</h3>
                    <p>Saved Addresses</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon green"><i class="fas fa-check-circle"></i></div>
                <div class="stat-content">
                    <h3>{{ $addresses->where('is_default', true)->count() }}</h3>
                    <p>Default Address</p>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon blue"><i class="fas fa-home"></i></div>
                <div class="stat-content">
                    <h3>{{ $addresses->where('address_type', 'home')->count() }}</h3>
                    <p>Home Addresses</p>
                </div>
            </div>
        </div>

        <div class="address-grid">
            <!-- Add Address Card -->
            <div class="add-address-card" onclick="openModal()">
                <i class="fas fa-plus-circle"></i>
                <p>Add New Address</p>
            </div>

            @foreach($addresses as $address)
                <div class="address-card {{ $address->is_default ? 'default' : '' }}">
                    @if($address->is_default)
                        <span class="default-badge"><i class="fas fa-check"></i> Default</span>
                    @endif
                    <span class="address-type {{ $address->address_type }}">
                        <i class="fas fa-{{ $address->address_type == 'home' ? 'home' : ($address->address_type == 'work' ? 'briefcase' : 'map-marker-alt') }}"></i>
                        {{ ucfirst($address->address_type) }}
                    </span>
                    <div class="address-name">{{ $address->full_name }}</div>
                    <div class="address-text">{{ $address->address_line1 }}</div>
                    @if($address->address_line2)
                        <div class="address-text">{{ $address->address_line2 }}</div>
                    @endif
                    <div class="address-text">{{ $address->city }}, {{ $address->state }} - {{ $address->pincode }}</div>
                    <div class="address-text">{{ $address->country }}</div>
                    <div class="address-phone"><i class="fas fa-phone"></i> {{ $address->phone }}</div>
                    <div class="address-actions">
                        <a onclick="editAddress({{ json_encode($address) }})"><i class="fas fa-edit"></i> Edit</a>
                        <a onclick="deleteAddress({{ $address->id }})" class="delete"><i class="fas fa-trash"></i> Remove</a>
                        @if(!$address->is_default)
                            <a onclick="setDefault({{ $address->id }})"><i class="fas fa-star"></i> Set Default</a>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

    </div>

    <!-- Add/Edit Modal -->
    <div class="modal-overlay" id="addressModal">
        <div class="modal">
            <div class="modal-header">
                <h3><i class="fas fa-map-marker-alt"></i> <span id="modalTitle">Add New Address</span></h3>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>
            <form id="addressForm">
                @csrf
                <input type="hidden" id="addressId" name="address_id" value="">
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-user"></i> Full Name</label>
                            <input type="text" name="full_name" id="full_name" placeholder="Enter full name" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-phone"></i> Mobile Number</label>
                            <input type="tel" name="phone" id="phone" placeholder="10-digit mobile" required maxlength="15">
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-home"></i> Address Line 1</label>
                        <input type="text" name="address_line1" id="address_line1" placeholder="House No., Building, Street" required>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-map"></i> Address Line 2 (Optional)</label>
                        <input type="text" name="address_line2" id="address_line2" placeholder="Area, Locality, Landmark">
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-city"></i> City</label>
                            <input type="text" name="city" id="city" placeholder="City name" required>
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-map-pin"></i> State</label>
                            <select name="state" id="state" required>
                                <option value="">Select State</option>
                                <option value="Andhra Pradesh">Andhra Pradesh</option>
                                <option value="Arunachal Pradesh">Arunachal Pradesh</option>
                                <option value="Assam">Assam</option>
                                <option value="Bihar">Bihar</option>
                                <option value="Chhattisgarh">Chhattisgarh</option>
                                <option value="Goa">Goa</option>
                                <option value="Gujarat">Gujarat</option>
                                <option value="Haryana">Haryana</option>
                                <option value="Himachal Pradesh">Himachal Pradesh</option>
                                <option value="Jharkhand">Jharkhand</option>
                                <option value="Karnataka">Karnataka</option>
                                <option value="Kerala">Kerala</option>
                                <option value="Madhya Pradesh">Madhya Pradesh</option>
                                <option value="Maharashtra">Maharashtra</option>
                                <option value="Manipur">Manipur</option>
                                <option value="Meghalaya">Meghalaya</option>
                                <option value="Mizoram">Mizoram</option>
                                <option value="Nagaland">Nagaland</option>
                                <option value="Odisha">Odisha</option>
                                <option value="Punjab">Punjab</option>
                                <option value="Rajasthan">Rajasthan</option>
                                <option value="Sikkim">Sikkim</option>
                                <option value="Tamil Nadu">Tamil Nadu</option>
                                <option value="Telangana">Telangana</option>
                                <option value="Tripura">Tripura</option>
                                <option value="Uttar Pradesh">Uttar Pradesh</option>
                                <option value="Uttarakhand">Uttarakhand</option>
                                <option value="West Bengal">West Bengal</option>
                                <option value="Delhi">Delhi</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label><i class="fas fa-hashtag"></i> PIN Code</label>
                            <input type="text" name="pincode" id="pincode" placeholder="6-digit PIN" required maxlength="6" pattern="[0-9]{6}">
                        </div>
                        <div class="form-group">
                            <label><i class="fas fa-tag"></i> Address Type</label>
                            <select name="address_type" id="address_type" required>
                                <option value="home">🏠 Home</option>
                                <option value="work">💼 Work</option>
                                <option value="other">📍 Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="is_default" id="is_default" value="1">
                            <label for="is_default"><i class="fas fa-star" style="color: #ff9900;"></i> Make this my default address</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitBtn"><i class="fas fa-save"></i> Save Address</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const modal = document.getElementById('addressModal');
        const form = document.getElementById('addressForm');
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const addressId = document.getElementById('addressId');

        function openModal() {
            form.reset();
            addressId.value = '';
            modalTitle.textContent = 'Add New Address';
            submitBtn.innerHTML = '<i class="fas fa-plus"></i> Add Address';
            modal.classList.add('active');
        }

        function closeModal() {
            modal.classList.remove('active');
        }

        function editAddress(address) {
            addressId.value = address.id;
            document.getElementById('full_name').value = address.full_name;
            document.getElementById('phone').value = address.phone;
            document.getElementById('address_line1').value = address.address_line1;
            document.getElementById('address_line2').value = address.address_line2 || '';
            document.getElementById('city').value = address.city;
            document.getElementById('state').value = address.state;
            document.getElementById('pincode').value = address.pincode;
            document.getElementById('address_type').value = address.address_type;
            document.getElementById('is_default').checked = address.is_default;
            
            modalTitle.textContent = 'Edit Address';
            submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Changes';
            modal.classList.add('active');
        }

        function deleteAddress(id) {
            if (!confirm('Are you sure you want to delete this address?')) return;
            
            fetch(`{{ url('profile/addresses') }}/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error deleting address');
                }
            });
        }

        function setDefault(id) {
            fetch(`{{ url('profile/addresses') }}/${id}/default`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error setting default');
                }
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const id = addressId.value;
            const url = id ? `{{ url('profile/addresses') }}/${id}` : '{{ route('profile.addresses.store') }}';
            const method = id ? 'PUT' : 'POST';
            
            // Build JSON data from form
            const data = {
                full_name: document.getElementById('full_name').value,
                phone: document.getElementById('phone').value,
                address_line1: document.getElementById('address_line1').value,
                address_line2: document.getElementById('address_line2').value,
                city: document.getElementById('city').value,
                state: document.getElementById('state').value,
                pincode: document.getElementById('pincode').value,
                address_type: document.getElementById('address_type').value,
                is_default: document.getElementById('is_default').checked ? 1 : 0
            };
            
            fetch(url, {
                method: method,
                body: JSON.stringify(data),
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'X-Requested-With': 'XMLHttpRequest',
                }
            }).then(r => r.json()).then(data => {
                if (data.success) {
                    location.reload();
                } else {
                    alert(data.message || 'Error saving address');
                }
            }).catch(() => {
                alert('Error saving address');
            });
        });

        // Close modal on outside click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) closeModal();
        });
    </script>

</body>
</html>
