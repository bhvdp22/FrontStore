<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;

class CustomerAuthController extends Controller
{
    public function showRegister()
    {
        return view('customer.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $customer = Customer::create($data);

        // Send welcome email using Resend SDK directly (HTTP API)
        try {
            $resendKey = config('resend.api_key') ?: env('RESEND_API_KEY');
            
            if ($resendKey) {
                // Render the Blade template to HTML
                $html = view('emails.welcome', ['customer' => $customer])->render();
                
                $resend = \Resend::client($resendKey);
                $resend->emails->send([
                    'from' => config('mail.from.name', 'FrontStore Team') . ' <' . config('mail.from.address', 'onboarding@resend.dev') . '>',
                    'to' => [$customer->email],
                    'subject' => 'Welcome to FrontStore! Here is your 10% Discount 🎉',
                    'html' => $html,
                ]);
                
                \Log::info('Welcome email sent to ' . $customer->email . ' via Resend');
            } else {
                \Log::warning('RESEND_API_KEY not configured, skipping welcome email for ' . $customer->email);
            }
        } catch (\Exception $e) {
            \Log::error('Customer welcome email failed for ' . $customer->email . ': ' . $e->getMessage());
        }

        session(['customer_email' => $customer->email, 'customer_name' => $customer->name, 'customer_id' => $customer->id]);
        $redirect = session('intended_url', route('shop.index'));
        session()->forget('intended_url');
        return redirect($redirect)->with('success', 'Welcome, '. $customer->name .'!');
    }

    public function showLogin()
    {
        return view('customer.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $customer = Customer::where('email', $data['email'])->first();
        if ($customer && $customer->password === $data['password']) {
            session(['customer_email' => $customer->email, 'customer_name' => $customer->name, 'customer_id' => $customer->id]);
            $redirect = session('intended_url', route('shop.index'));
            session()->forget('intended_url');
            return redirect($redirect)->with('success', 'Logged in');
        }

        return back()->withErrors(['login' => 'Invalid credentials'])->withInput();
    }

    public function logout()
    {
        session()->forget(['customer_email', 'customer_name', 'customer_id']);
        return redirect()->route('customer.login')->with('success', 'Logged out');
    }
}
