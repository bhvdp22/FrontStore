<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeCustomerMail;

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

        // Send welcome email after HTTP response is sent (prevents 502 timeout)
        $customerEmail = $customer->email;
        $customerCopy = $customer->toArray();
        app()->terminating(function () use ($customerEmail, $customerCopy) {
            try {
                $customer = new \App\Models\Customer($customerCopy);
                Mail::to($customerEmail)->send(new WelcomeCustomerMail($customer));
            } catch (\Exception $e) {
                \Log::error('Customer welcome email failed for ' . $customerEmail . ': ' . $e->getMessage());
            }
        });

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
