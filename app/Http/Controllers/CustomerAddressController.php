<?php

namespace App\Http\Controllers;

use App\Models\CustomerAddress;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerAddressController extends Controller
{
    private function getCustomerId()
    {
        $email = session('customer_email');
        if (!$email) return null;
        
        $customer = Customer::where('email', $email)->first();
        return $customer ? $customer->id : null;
    }

    public function index()
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return redirect()->route('customer.login')->with('error', 'Please login to continue.');
        }

        $addresses = CustomerAddress::where('customer_id', $customerId)
            ->orderBy('is_default', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('profile.addresses', compact('addresses'));
    }

    public function store(Request $request)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.']);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'address_type' => 'required|in:home,work,other',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['customer_id'] = $customerId;
        $validated['country'] = $validated['country'] ?? 'India';
        $validated['is_default'] = $request->has('is_default');

        // If setting as default, unset others
        if ($validated['is_default']) {
            CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
        }

        // If first address, make it default
        $existingCount = CustomerAddress::where('customer_id', $customerId)->count();
        if ($existingCount === 0) {
            $validated['is_default'] = true;
        }

        $address = CustomerAddress::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Address added successfully!', 'address' => $address]);
        }

        return redirect()->route('profile.addresses')->with('success', 'Address added successfully!');
    }

    public function update(Request $request, $id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.']);
        }

        $address = CustomerAddress::where('id', $id)->where('customer_id', $customerId)->first();
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found.']);
        }

        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'address_line1' => 'required|string',
            'address_line2' => 'nullable|string',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'pincode' => 'required|string|max:10',
            'country' => 'nullable|string|max:100',
            'address_type' => 'required|in:home,work,other',
            'is_default' => 'nullable|boolean',
        ]);

        $validated['is_default'] = $request->has('is_default');

        // If setting as default, unset others
        if ($validated['is_default']) {
            CustomerAddress::where('customer_id', $customerId)->where('id', '!=', $id)->update(['is_default' => false]);
        }

        $address->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Address updated successfully!']);
        }

        return redirect()->route('profile.addresses')->with('success', 'Address updated successfully!');
    }

    public function destroy($id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.']);
        }

        $address = CustomerAddress::where('id', $id)->where('customer_id', $customerId)->first();
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found.']);
        }

        $wasDefault = $address->is_default;
        $address->delete();

        // If deleted address was default, set another as default
        if ($wasDefault) {
            $newDefault = CustomerAddress::where('customer_id', $customerId)->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Address deleted successfully!']);
        }

        return redirect()->route('profile.addresses')->with('success', 'Address deleted successfully!');
    }

    public function setDefault($id)
    {
        $customerId = $this->getCustomerId();
        if (!$customerId) {
            return response()->json(['success' => false, 'message' => 'Please login to continue.']);
        }

        $address = CustomerAddress::where('id', $id)->where('customer_id', $customerId)->first();
        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found.']);
        }

        // Unset all defaults
        CustomerAddress::where('customer_id', $customerId)->update(['is_default' => false]);
        
        // Set this as default
        $address->update(['is_default' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true, 'message' => 'Default address updated!']);
        }

        return redirect()->route('profile.addresses')->with('success', 'Default address updated!');
    }
}
