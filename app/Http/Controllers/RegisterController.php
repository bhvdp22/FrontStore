<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail; 
use App\Mail\WelcomeSellerMail;    

class RegisterController extends Controller
{
    public function create()
    {
        return view('register');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            // Basic Info
            'name' => ['required', 'string', 'max:255', 'unique:user,name'],
            'phone' => ['required', 'digits:10', 'unique:user,phone'],
            'email' => ['nullable', 'email', 'max:255'],
            'country_code' => ['nullable', 'string', 'max:5'],
            'password' => ['required', 'string', 'min:6'],
            
            // Business Details
            'business_name' => ['required', 'string', 'max:255'],
            'business_address' => ['required', 'string', 'max:500'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:10'],
            'country' => ['nullable', 'string', 'max:100'],
            
            // Tax & Legal
            'gstin' => ['nullable', 'string', 'max:20'],
            'pan' => ['nullable', 'string', 'max:15'],
            'cin' => ['nullable', 'string', 'max:25'],
        ]);

        $user = User::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'] ?? null,
            'country_code' => $data['country_code'] ?? 'IN',
            // SECURED: Passwords must always be hashed before saving to the database
            'password' => Hash::make($data['password']), 
            
            // Business Details
            'business_name' => $data['business_name'],
            'business_address' => $data['business_address'],
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'country' => $data['country'] ?? 'India',
            
            // Tax & Legal
            'gstin' => $data['gstin'] ?? null,
            'pan' => $data['pan'] ?? null,
            'cin' => $data['cin'] ?? null,
        ]);

        // Send welcome email to the new seller
        if ($user->email) {
            try {
                Mail::to($user->email)->send(new WelcomeSellerMail($user));
            } catch (\Exception $e) {
                \Log::error('Seller welcome email failed for ' . $user->email . ': ' . $e->getMessage());
            }
        }

        session(['loginusername' => $user->name]);

        return redirect('/')->with('success', 'Registration successful. You are now logged in.');
    }
}