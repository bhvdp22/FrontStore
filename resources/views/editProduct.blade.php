@extends('layouts.seller')

@section('title', 'Edit Product - Seller Central')

@section('extra_styles')
<style>
    body { font-family: 'Poppins', sans-serif; margin: 0; background-color: #f1f3f3; }
    .container { max-width: 800px; margin: 30px auto; padding: 0; }
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
    .page-title { font-size: 24px; font-weight: 700; color: #0f1111; margin: 0; }
    .page-subtitle { font-size: 13px; color: #565959; margin: 8px 0 0 0; }

    .btn {
        padding: 10px 18px; border-radius: 4px; font-size: 13px; font-weight: 600;
        border: 1px solid; cursor: pointer; text-decoration: none;
        display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s;
    }
    .btn-back { background: white; color: #0f1111; border-color: #888; }
    .btn-back:hover { background: #f0f2f2; text-decoration: none; }
    .btn-primary { background: #ff9900; color: white; border-color: #ff9900; }
    .btn-primary:hover { background: #e88b00; }
    .btn-secondary { background: white; color: #0f1111; border-color: #888; margin-left: 10px; }
    .btn-secondary:hover { background: #f0f2f2; text-decoration: none; }

    .form-group { margin-bottom: 20px; }
    label { display: block; font-weight: 700; margin-bottom: 6px; font-size: 13px; color: #0f1111; }
    input[type="text"], input[type="number"], textarea, select {
        width: 100%; padding: 10px 12px; border: 1px solid #888; border-radius: 4px;
        font-size: 14px; box-sizing: border-box; font-family: inherit;
    }
    input:focus, textarea:focus, select:focus {
        border-color: #e77600; box-shadow: 0 0 0 3px rgba(228,121,17, 0.1); outline: none;
    }
    textarea { min-height: 80px; resize: vertical; }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    .alert-error { color: #c40000; font-size: 12px; margin-top: 6px; display: block; }
    .help-text { font-size: 12px; color: #565959; margin-top: 4px; display: block; }
    .required { color: #c40000; }

    /* Image Upload Area */
    .image-upload-area {
        border: 2px dashed #ccc; border-radius: 8px; padding: 30px; text-align: center;
        cursor: pointer; transition: all 0.2s; background: #fafafa;
    }
    .image-upload-area:hover { border-color: #e77600; background: #fff8ef; }
    .image-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(110px, 1fr)); gap: 12px; margin-top: 12px; }
    .img-item {
        position: relative; border: 2px solid #e5e7eb; border-radius: 6px;
        overflow: hidden; aspect-ratio: 1; background: #f9f9f9;
    }
    .img-item.is-primary { border-color: #067d62; }
    .img-item img { width: 100%; height: 100%; object-fit: cover; }
    .img-item .img-actions {
        position: absolute; top: 0; left: 0; right: 0;
        display: flex; justify-content: space-between; padding: 4px;
    }
    .img-item .remove-btn {
        width: 22px; height: 22px; border-radius: 50%; background: #c40000; color: #fff;
        border: none; font-size: 12px; cursor: pointer; display: flex;
        align-items: center; justify-content: center;
    }
    .img-item .primary-radio {
        display: flex; align-items: center; gap: 3px; background: rgba(255,255,255,0.9);
        padding: 2px 6px; border-radius: 3px; font-size: 10px; font-weight: 600;
    }
    .img-item .primary-radio input { width: 12px; height: 12px; accent-color: #067d62; }
    .img-item .primary-badge {
        position: absolute; bottom: 4px; left: 4px; background: #067d62; color: #fff;
        font-size: 9px; padding: 2px 6px; border-radius: 3px; font-weight: 600;
    }
    .img-preview-item {
        position: relative; border: 2px solid #e5e7eb; border-radius: 6px;
        overflow: hidden; aspect-ratio: 1; background: #f9f9f9;
    }
    .img-preview-item img { width: 100%; height: 100%; object-fit: cover; }
    .img-preview-item .remove-img {
        position: absolute; top: 4px; right: 4px; width: 22px; height: 22px;
        border-radius: 50%; background: #c40000; color: #fff; border: none;
        font-size: 12px; cursor: pointer; display: flex; align-items: center; justify-content: center;
    }
    .img-preview-item .new-badge {
        position: absolute; bottom: 4px; left: 4px; background: #e77600; color: #fff;
        font-size: 9px; padding: 2px 6px; border-radius: 3px; font-weight: 600;
    }
    .section-title {
        font-family: 'Montserrat', sans-serif;
        font-size: 15px; font-weight: 700; color: #0f1111;
        margin-bottom: 12px; padding-bottom: 8px; border-bottom: 1px solid #eee;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="page-header">
        <div>
            <h1 class="page-title">Edit Product</h1>
            <p class="page-subtitle">Update details and images for "{{ $product->name }}"</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-back">
            <i class="fas fa-arrow-left"></i> Back to Inventory
        </a>
    </div>

    <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        {{-- Product Details Card --}}
        <div class="form-card">
            <div class="section-title">Product Details</div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" style="width: 100%; padding: 10px; border: 1px solid #888; border-radius: 4px;">
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Product Name <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required>
                @error('name') <span class="alert-error">{{ $message }}</span> @enderror
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>SKU <span class="required">*</span></label>
                    <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" required>
                    @error('sku') <span class="alert-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>ASIN <span class="required">*</span></label>
                    <input type="text" name="asin" value="{{ old('asin', $product->asin) }}" required>
                    @error('asin') <span class="alert-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Price (₹) <span class="required">*</span></label>
                    <input type="number" step="0.01" name="price" value="{{ old('price', $product->price) }}" required>
                    @error('price') <span class="alert-error">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <label>Quantity <span class="required">*</span></label>
                    <input type="number" name="quantity" value="{{ old('quantity', $product->quantity) }}" required>
                    @error('quantity') <span class="alert-error">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description">{{ old('description', $product->description) }}</textarea>
            </div>
        </div>

        {{-- Images Card --}}
        <div class="form-card">
            <div class="section-title">Product Images</div>

            {{-- Existing Images --}}
            @php $existingImages = $product->images()->orderBy('sort_order')->get(); @endphp
            @if($existingImages->count() > 0)
                <label>Current Images (click radio to set as primary, × to remove)</label>
                <div class="image-grid" id="existingImages">
                    @foreach($existingImages as $img)
                        <div class="img-item {{ $img->is_primary ? 'is-primary' : '' }}" id="img-item-{{ $img->id }}">
                            <img src="{{ asset($img->image_path) }}" alt="Product image" onerror="this.src='https://placehold.co/150?text=No+Image'">
                            <div class="img-actions">
                                <label class="primary-radio">
                                    <input type="radio" name="primary_image" value="{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }}>
                                    Main
                                </label>
                                <button type="button" class="remove-btn" onclick="markForDeletion({{ $img->id }})" title="Remove">&times;</button>
                            </div>
                            @if($img->is_primary)
                                <span class="primary-badge">PRIMARY</span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <div id="deleteInputs"></div>
            @else
                <p style="color: #888; font-size: 13px; margin-bottom: 12px;">No images uploaded yet.</p>
            @endif

            {{-- Upload New Images --}}
            <div style="margin-top: 16px;">
                <label>Add New Images</label>
                <div class="image-upload-area" onclick="document.getElementById('productImages').click()">
                    <i class="fas fa-cloud-upload-alt" style="font-size: 28px; color: #888; margin-bottom: 6px;"></i>
                    <p style="margin: 0; font-size: 13px; color: #555;">Click to upload more images</p>
                    <p style="margin: 4px 0 0; font-size: 11px; color: #888;">JPG, PNG, WebP — Max 5MB each — Up to 8 total</p>
                </div>
                <input type="file" name="product_images[]" id="productImages" multiple accept="image/*" style="display:none" onchange="previewNewImages(this)">
                <div id="newImagePreviewGrid" class="image-grid"></div>
                @error('product_images') <span class="alert-error">{{ $message }}</span> @enderror
                @error('product_images.*') <span class="alert-error">{{ $message }}</span> @enderror
            </div>

            {{-- Fallback Image URL --}}
            <div class="form-group" style="margin-top: 16px;">
                <label>Or Image URL</label>
                <input type="text" name="img_path" value="{{ old('img_path', $product->img_path) }}">
                <span class="help-text">Fallback URL if no images are uploaded</span>
            </div>
        </div>

        <div style="display: flex; gap: 10px; margin-bottom: 40px;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-check"></i> Save Changes
            </button>
            <a class="btn btn-secondary" href="{{ route('products.index') }}">
                <i class="fas fa-times"></i> Cancel
            </a>
        </div>
    </form>
</div>

<script>
    // Mark existing image for deletion
    function markForDeletion(imgId) {
        const el = document.getElementById('img-item-' + imgId);
        if (el) {
            el.style.opacity = '0.3';
            el.style.pointerEvents = 'none';
            const inp = document.createElement('input');
            inp.type = 'hidden';
            inp.name = 'delete_images[]';
            inp.value = imgId;
            document.getElementById('deleteInputs').appendChild(inp);
        }
    }

    // Preview new images
    let newFiles = [];
    function previewNewImages(input) {
        const grid = document.getElementById('newImagePreviewGrid');
        const files = Array.from(input.files);
        
        files.forEach((file, i) => {
            if (file.size > 5 * 1024 * 1024) {
                alert(file.name + ' is too large (max 5MB)');
                return;
            }
            newFiles.push(file);
            const reader = new FileReader();
            reader.onload = function(e) {
                const idx = newFiles.length - files.length + i;
                const div = document.createElement('div');
                div.className = 'img-preview-item';
                div.dataset.index = idx;
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-img" onclick="removeNewImage(${idx})" title="Remove">&times;</button>
                    <span class="new-badge">NEW</span>
                `;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
        updateNewFileInput();
    }

    function removeNewImage(index) {
        newFiles.splice(index, 1);
        rebuildNewPreviews();
        updateNewFileInput();
    }

    function rebuildNewPreviews() {
        const grid = document.getElementById('newImagePreviewGrid');
        grid.innerHTML = '';
        newFiles.forEach((file, i) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.className = 'img-preview-item';
                div.dataset.index = i;
                div.innerHTML = `
                    <img src="${e.target.result}" alt="Preview">
                    <button type="button" class="remove-img" onclick="removeNewImage(${i})" title="Remove">&times;</button>
                    <span class="new-badge">NEW</span>
                `;
                grid.appendChild(div);
            };
            reader.readAsDataURL(file);
        });
    }

    function updateNewFileInput() {
        const dt = new DataTransfer();
        newFiles.forEach(f => dt.items.add(f));
        document.getElementById('productImages').files = dt.files;
    }
</script>
@endsection