<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

class SellerProfileController extends Controller
{
    /**
     * Display seller profile
     */
    public function show()
    {
        $username = session('loginusername');
        
        if (!$username) {
            return redirect('/login')->with('error', 'Please login first');
        }

        $seller = User::where('name', $username)->first();

        if (!$seller) {
            return redirect('/login')->with('error', 'Seller not found');
        }

        return view('seller.profile', compact('seller'));
    }

    /**
     * Show edit profile form
     */
    public function edit()
    {
        $username = session('loginusername');
        
        if (!$username) {
            return redirect('/login')->with('error', 'Please login first');
        }

        $seller = User::where('name', $username)->first();

        if (!$seller) {
            return redirect('/login')->with('error', 'Seller not found');
        }

        return view('seller.edit-profile', compact('seller'));
    }

    /**
     * Update seller profile
     */
    public function update(Request $request)
    {
        $username = session('loginusername');
        
        if (!$username) {
            return redirect('/login')->with('error', 'Please login first');
        }

        $seller = User::where('name', $username)->first();

        if (!$seller) {
            return redirect('/login')->with('error', 'Seller not found');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:user,email,' . $seller->id,
            'business_name' => 'required|string|max:255',
            'business_address' => 'required|string|max:500',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'gstin' => 'nullable|string|max:15',
            'pan' => 'nullable|string|max:10',
            'cin' => 'nullable|string|max:21',
            'bank_name' => 'nullable|string|max:255',
            'bank_account' => 'nullable|string|max:50',
            'ifsc_code' => 'nullable|string|max:20',
            // Storefront fields
            'brand_story' => 'nullable|string|max:5000',
            'banner_image' => 'nullable|image|max:2048',
            'logo' => 'nullable|image|max:1024',
            'social_links.website' => 'nullable|url|max:500',
            'social_links.instagram' => 'nullable|string|max:500',
            'social_links.facebook' => 'nullable|string|max:500',
            'social_links.twitter' => 'nullable|string|max:500',
            'storefront_enabled' => 'nullable|boolean',
        ]);

        // Update session if name changed
        if ($seller->name !== $validated['name']) {
            session(['loginusername' => $validated['name']]);
        }

        // Handle banner_image upload
        if ($request->hasFile('banner_image')) {
            $result = cloudinary()->uploadApi()->upload($request->file('banner_image')->getRealPath(), [
                'folder' => 'FrontStore/storefront/banners'
            ]);
            $validated['banner_image'] = $result['secure_url'];
        } else {
            unset($validated['banner_image']);
        }

        // Handle logo upload
        if ($request->hasFile('logo')) {
            $result = cloudinary()->uploadApi()->upload($request->file('logo')->getRealPath(), [
                'folder' => 'FrontStore/storefront/logos'
            ]);
            $validated['logo'] = $result['secure_url'];
        } else {
            unset($validated['logo']);
        }

        // Build social_links JSON from sub-fields
        $socialLinks = array_filter($request->input('social_links', []), fn($v) => !empty($v));
        $validated['social_links'] = !empty($socialLinks) ? $socialLinks : null;

        // Storefront toggle (checkbox sends nothing when unchecked)
        $validated['storefront_enabled'] = $request->has('storefront_enabled') ? true : false;

        // Auto-generate slug from business_name if storefront enabled and slug empty
        if ($validated['storefront_enabled'] && empty($seller->slug)) {
            $baseSlug = Str::slug($validated['business_name']);
            $slug = $baseSlug;
            $counter = 1;
            while (User::where('slug', $slug)->where('id', '!=', $seller->id)->exists()) {
                $slug = $baseSlug . '-' . $counter;
                $counter++;
            }
            $validated['slug'] = $slug;
        }

        $seller->update($validated);

        return redirect()->route('seller.profile')->with('success', 'Profile updated successfully!');
    }
}
