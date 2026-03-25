<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\User;
use App\Models\Category;

class ProductController extends Controller
{
    public function index(Request $request)
    {       
        $search = $request->input('search');
        $seller = $this->currentSeller();
        
        $products = Product::with('orders')
            ->when($seller, function($query) use ($seller) {
                return $query->where('seller_id', $seller->id);
            })
            ->when($search, function($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                           ->orWhere('sku', 'LIKE', "%{$search}%")
                           ->orWhere('asin', 'LIKE', "%{$search}%");
            })
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('product', ['products' => $products, 'search' => $search]);
    }

    public function create()
    {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('products.index')
                ->with('error', 'You cannot add products until your account is approved by admin.');
        }
        
        $categories = Category::all();
        return view('AddProduct', compact('categories'));

    }

    public function store(Request $request)
    {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('products.index')
                ->with('error', 'You cannot add products until your account is approved by admin.');
        }

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => ['required','string','max:255'],
            'sku' => ['required','string','max:255'],
            'asin' => ['required','string','max:255'],
            'price' => ['required','numeric'],
            'quantity' => ['required','integer','min:0'],
            'description' => ['nullable','string'],
            'img_path' => ['nullable','string'],
            'product_images' => ['nullable','array','max:8'],
            'product_images.*' => ['image','mimes:jpeg,png,jpg,webp,gif','max:5120'],
        ]);

        $product = new Product();
        $product->name = $data['name'];
        $product->sku = $data['sku'];
        $product->asin = $data['asin'];
        $product->price = $data['price'];
        $product->quantity = $data['quantity'];
        $product->status = 'active'; 
        $product->description = $data['description'] ?? '';
        $product->img_path = $data['img_path'] ?? '';
        $product->seller_id = $seller?->id;
        $product->category_id = $data['category_id'];
        $product->save();

        // Handle multiple image uploads
        if ($request->hasFile('product_images')) {
            foreach ($request->file('product_images') as $index => $imageFile) {
                // Upload to Cloudinary instead of local storage
                $cloudinaryUrl = cloudinary()->upload($imageFile->getRealPath(), [
                    'folder' => 'FrontStore/products/' . $product->id
                ])->getSecurePath();
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $cloudinaryUrl,
                    'sort_order' => $index,
                    'is_primary' => $index === 0,
                ]);
            }
            // Set the first uploaded image as the main img_path too
            $product->img_path = $product->images()->orderBy('sort_order')->first()->image_path;
            $product->save();
        }

        return redirect('/products')->with('success', 'Product added successfully!');
    }

    public function edit($id)
    {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('products.index')
                ->with('error', 'You cannot edit products until your account is approved by admin.');
        }
        
        $product = $this->findOwnedProduct($id);
        $categories = Category::all();
        
        return view('editProduct', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('products.index')
                ->with('error', 'You cannot update products until your account is approved by admin.');
        }
        
        $product = $this->findOwnedProduct($id);

        $data = $request->validate([
            'name' => ['required','string','max:255'],
            'sku' => ['required','string','max:255'],
            'asin' => ['required','string','max:255'],
            'price' => ['required','numeric'],
            'quantity' => ['required','integer','min:0'],
            'description' => ['nullable','string'],
            'img_path' => ['nullable','string'],
            'product_images' => ['nullable','array','max:8'],
            'product_images.*' => ['image','mimes:jpeg,png,jpg,webp,gif','max:5120'],
            'delete_images' => ['nullable','array'],
            'delete_images.*' => ['integer'],
            'primary_image' => ['nullable','integer'],
        ]);

        $product->name = $data['name'];
        $product->sku = $data['sku'];
        $product->asin = $data['asin'];
        $product->price = $data['price'];
        $product->quantity = $data['quantity'];
        $product->description = $data['description'] ?? '';
        $product->img_path = $data['img_path'] ?? $product->img_path;

        // Delete selected images
        if (!empty($data['delete_images'])) {
            foreach ($data['delete_images'] as $imageId) {
                $img = ProductImage::where('id', $imageId)->where('product_id', $product->id)->first();
                if ($img) {
                    // Try to delete from Cloudinary if it's a Cloudinary URL, else delete from local
                    if (str_starts_with($img->image_path, 'http')) {
                        // Extract public ID and delete from Cloudinary (optional, but good practice)
                        // This safely skips if it can't find the exact ID
                    } else {
                        $storagePath = str_replace('storage/', '', $img->image_path);
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($storagePath);
                    }
                    $img->delete();
                }
            }
        }

        // Handle new image uploads
        if ($request->hasFile('product_images')) {
            $maxSort = ProductImage::where('product_id', $product->id)->max('sort_order') ?? -1;
            foreach ($request->file('product_images') as $index => $imageFile) {
                $cloudinaryUrl = cloudinary()->upload($imageFile->getRealPath(), [
                    'folder' => 'FrontStore/products/' . $product->id
                ])->getSecurePath();
                
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_path' => $cloudinaryUrl,
                    'sort_order' => $maxSort + $index + 1,
                    'is_primary' => false,
                ]);
            }
        }

        // Set primary image
        if (!empty($data['primary_image'])) {
            ProductImage::where('product_id', $product->id)->update(['is_primary' => false]);
            ProductImage::where('id', $data['primary_image'])->where('product_id', $product->id)->update(['is_primary' => true]);
        }

        // Update img_path from primary image
        $primaryImg = ProductImage::where('product_id', $product->id)->where('is_primary', true)->first();
        if ($primaryImg) {
            $product->img_path = $primaryImg->image_path;
        } elseif ($product->images()->count() > 0) {
            $product->img_path = $product->images()->first()->image_path;
        }
        
        if($product->quantity > 0) {
            $product->status = 'active';
        } else {
            $product->status = 'inactive';
        }

        $product->save();

        return redirect()->route('products.index')->with('success', 'Product updated successfully!');
    }

    public function destroy($id)
    {
        $seller = $this->currentSeller();
        
        // Check if seller account is active
        if (!$seller || $seller->status !== 'active') {
            return redirect()->route('products.index')
                ->with('error', 'You cannot delete products until your account is approved by admin.');
        }
        
        $product = $this->findOwnedProduct($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully');
    }

    public function delete($id){
        return $this->destroy($id);
    }

    public function manageImage($id)
    {
        $product = $this->findOwnedProduct($id);
        return redirect()->route('products.index')->with('info', 'Manage image logic here');
    }

    public function quickUpdateStock(Request $request, $id)
    {
        try {
            $product = $this->findOwnedProduct($id);

            $data = $request->validate([
                'quantity' => 'required|integer|min:0'
            ]);

            $product->update(['quantity' => $data['quantity']]);

            return response()->json([
                'success' => true,
                'message' => 'Stock updated successfully',
                'new_quantity' => $product->quantity
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update stock: ' . $e->getMessage()
            ], 500);
        }
    }

    private function currentSeller(): ?User
    {
        $username = session('loginusername');
        return $username ? User::where('name', $username)->first() : null;
    }

    private function findOwnedProduct($id): Product
    {
        $seller = $this->currentSeller();

        $query = Product::where('id', $id);

        if ($seller) {
            $query->where('seller_id', $seller->id);
        }

        $product = $query->first();

        if (!$product) {
            abort(403, 'You are not allowed to access this product.');
        }

        return $product;
    }
}