@extends('layouts.seller')

@section('title', 'Add Product - Seller Central')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f1f3f3; }

    /* Container */
    .container { max-width: 800px; margin: 30px auto; padding: 0; }
    
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
    
    .btn {
        padding: 10px 18px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        border: 1px solid;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 6px;
        transition: all 0.2s;
    }
    
    .btn-back {
        background: white;
        color: #0f1111;
        border-color: #888;
    }
    
    .btn-back:hover {
        background: #f0f2f2;
        text-decoration: none;
    }
    
    .btn-primary {
        background: #ff9900;
        color: white;
        border-color: #ff9900;
    }
    
    .btn-primary:hover {
        background: #e88b00;
    }
    
    .btn-secondary {
        background: white;
        color: #0f1111;
        border-color: #888;
        margin-left: 10px;
    }
    
    .btn-secondary:hover {
        background: #f0f2f2;
        text-decoration: none;
    }

    /* Form Elements */
    .form-group { margin-bottom: 20px; }
    
    label {
        display: block;
        font-weight: 700;
        margin-bottom: 6px;
        font-size: 13px;
        color: #0f1111;
    }
    
    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #888;
        border-radius: 4px;
        font-size: 14px;
        box-sizing: border-box;
        font-family: inherit;
    }
    
    input:focus,
    textarea:focus {
        border-color: #e77600;
        box-shadow: 0 0 0 3px rgba(228,121,17, 0.1);
        outline: none;
    }
    
    textarea {
        min-height: 80px;
        resize: vertical;
    }
    
    /* Grid Layout for Form */
    .form-row {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 20px;
    }
    
    /* Error Messages */
    .alert-error {
        color: #c40000;
        font-size: 12px;
        margin-top: 6px;
        display: block;
    }
    
    /* Help Text */
    .help-text {
        font-size: 12px;
        color: #565959;
        margin-top: 4px;
        display: block;
    }
    
    .required {
        color: #c40000;
    }

    /* Image Upload Area */
    .image-upload-area {
        border: 2px dashed #ccc;
        border-radius: 8px;
        padding: 30px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #fafafa;
    }
    .image-upload-area:hover {
        border-color: #e77600;
        background: #fff8ef;
    }
    .img-preview-item {
        position: relative;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        overflow: hidden;
        aspect-ratio: 1;
        background: #f9f9f9;
    }
    .img-preview-item img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .img-preview-item .remove-img {
        position: absolute;
        top: 4px;
        right: 4px;
        width: 22px;
        height: 22px;
        border-radius: 50%;
        background: #c40000;
        color: #fff;
        border: none;
        font-size: 12px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .img-preview-item .primary-badge {
        position: absolute;
        bottom: 4px;
        left: 4px;
        background: #067d62;
        color: #fff;
        font-size: 9px;
        padding: 2px 6px;
        border-radius: 3px;
        font-weight: 600;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Add a New Product</h1>
            <p class="page-subtitle">Fill in the details below to add a product to your inventory</p>
        </div>
        <a href="/products" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <div class="form-card">
    <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Category <span class="required">*</span></label>
            <select name="category_id" class="form-control" required style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px;">
                <option value="">-- Select a Category --</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            <span class="help-text">Choose the category that best fits your product</span>
            @error('category_id') <span class="alert-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Product Name <span class="required">*</span></label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="Enter product name">
            <span class="help-text">Enter a clear, descriptive product name</span>
            @error('name') <span class="alert-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Seller SKU <span class="required">*</span></label>
                <input type="text" name="sku" value="{{ old('sku') }}" required placeholder="e.g., ABC-123">
                <span class="help-text">Unique identifier for your product</span>
                @error('sku') <span class="alert-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>FSIN <span class="required">*</span></label>
                <input type="text" name="asin" value="{{ old('asin') }}" required placeholder="e.g., B08N5WRWNW">
                <span class="help-text">FrontStore Standard Identification Number</span>
                @error('asin') <span class="alert-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label>Your Price (₹) <span class="required">*</span></label>
                <input type="number" step="0.01" name="price" value="{{ old('price') }}" required placeholder="0.00">
                <span class="help-text">Price per unit (excluding taxes)</span>
                @error('price') <span class="alert-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-group">
                <label>Quantity <span class="required">*</span></label>
                <input type="number" min="0" name="quantity" value="{{ old('quantity', 0) }}" required placeholder="0">
                <span class="help-text">Available stock quantity</span>
                @error('quantity') <span class="alert-error">{{ $message }}</span> @enderror
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" placeholder="Enter product description (optional)">{{ old('description') }}</textarea>
            <span class="help-text">Describe your product features and benefits</span>
        </div>

        <div class="form-group">
            <label>Product Images <span class="required">*</span></label>
            <div class="image-upload-area" id="imageUploadArea" onclick="document.getElementById('productImages').click()">
                <i class="fas fa-cloud-upload-alt" style="font-size: 32px; color: #888; margin-bottom: 8px;"></i>
                <p style="margin: 0; font-size: 14px; color: #555;">Click to upload images</p>
                <p style="margin: 4px 0 0; font-size: 11px; color: #888;">JPG, PNG, WebP — Max 5MB each — Up to 8 images</p>
            </div>
            <input type="file" name="product_images[]" id="productImages" multiple accept="image/*" style="display:none" onchange="previewImages(this)">
            <div id="imagePreviewGrid" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(100px, 1fr)); gap: 10px; margin-top: 10px;"></div>
            @error('product_images') <span class="alert-error">{{ $message }}</span> @enderror
            @error('product_images.*') <span class="alert-error">{{ $message }}</span> @enderror
        </div>

        <div class="form-group">
            <label>Or Enter Image URL</label>
            <input type="text" name="img_path" value="{{ old('img_path') }}" placeholder="https://example.com/image.jpg">
            <span class="help-text">Alternatively, paste a direct image URL (uploaded images take priority)</span>
        </div>

        <div style="margin-top: 30px; display: flex; gap: 10px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i> Save and Finish
            </button>
            <a class="btn btn-secondary" href="/products">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
    </div>
</div>

<script>
    let selectedFiles = [];

    function previewImages(input) {
        const grid = document.getElementById('imagePreviewGrid');
        const files = Array.from(input.files);
        
        if (selectedFiles.length + files.length > 8) {
            alert('Maximum 8 images allowed');
            return;
        }

        files.forEach((file, i) => {
            if (file.size > 5 * 1024 * 1024) {
                alert(file.name + ' is too large (max 5MB)');
                return;
            }
            selectedFiles.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                const idx = selectedFiles.length - files.length + i;
                const div = document.createElement('div');
                div.className = 'img-preview-item';
                div.dataset.index = idx;
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-img" onclick="removeImage(${idx})" title="Remove">&times;</button>
                    ${idx === 0 ? '<span class="primary-badge">PRIMARY</span>' : ''}
                `;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });

        updateFileInput();
    }

    function removeImage(index) {
        selectedFiles.splice(index, 1);
        rebuildPreviews();
        updateFileInput();
    }

    function rebuildPreviews() {
        const grid = document.getElementById('imagePreviewGrid');
        grid.innerHTML = '';
        selectedFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'img-preview-item';
                div.dataset.index = i;
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-img" onclick="removeImage(${i})" title="Remove">&times;</button>
                    ${i === 0 ? '<span class="primary-badge">PRIMARY</span>' : ''}
                `;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function updateFileInput() {
        const dt = new DataTransfer();
        selectedFiles.forEach(f => dt.items.add(f));
        document.getElementById('productImages').files = dt.files;
    }
</script>
@endsection