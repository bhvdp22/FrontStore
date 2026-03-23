<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BusinessSetting;

class AdminSettingsController extends Controller
{
    // 1. Show the Settings Page
    public function index()
    {
        // Get settings using the singleton pattern
        $settings = BusinessSetting::current();

        return view('admin.setting', compact('settings'));
    }

    // 2. Save the Updates
    public function update(Request $request)
    {
        $request->validate([
            'admin_commission' => 'required|numeric|min:0|max:100',
            'platform_fee' => 'required|numeric|min:0',
            'gst_percentage' => 'required|numeric|min:0|max:100',
            'tax_label' => 'required|string|max:50',
            'platform_fee_label' => 'required|string|max:50',
            'business_name' => 'nullable|string|max:255',
            'business_gstin' => 'nullable|string|max:50',
            'business_address' => 'nullable|string',
        ]);

        $settings = BusinessSetting::current();
        
        $settings->update([
            'admin_commission' => $request->admin_commission,
            'platform_fee' => $request->platform_fee,
            'gst_percentage' => $request->gst_percentage,
            'tax_included_in_price' => $request->has('tax_included_in_price'),
            'show_tax_on_invoice' => $request->has('show_tax_on_invoice'),
            'show_platform_fee' => $request->has('show_platform_fee'),
            'tax_label' => $request->tax_label,
            'platform_fee_label' => $request->platform_fee_label,
            'business_name' => $request->business_name,
            'business_gstin' => $request->business_gstin,
            'business_address' => $request->business_address,
        ]);

        return back()->with('success', 'Business settings updated successfully!');
    }
}