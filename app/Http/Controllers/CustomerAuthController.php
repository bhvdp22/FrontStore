<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use Illuminate\Support\Facades\Http;

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

        // Send welcome email via SendGrid HTTP API (no SMTP needed)
        $this->sendWelcomeEmail($customer);

        session(['customer_email' => $customer->email, 'customer_name' => $customer->name, 'customer_id' => $customer->id]);
        $redirect = session('intended_url', route('shop.index'));
        session()->forget('intended_url');
        return redirect($redirect)->with('success', 'Welcome, '. $customer->name .'!');
    }

    private function sendWelcomeEmail(Customer $customer): void
    {
        try {
            $apiKey = config('services.sendgrid.api_key') ?: env('SENDGRID_API_KEY');
            
            if (!$apiKey) {
                \Log::warning('SENDGRID_API_KEY not configured, skipping welcome email for ' . $customer->email);
                return;
            }

            $html = view('emails.welcome', ['customer' => $customer])->render();

            $response = Http::withToken($apiKey)
                ->timeout(10)
                ->post('https://api.sendgrid.com/v3/mail/send', [
                    'personalizations' => [
                        [
                            'to' => [['email' => $customer->email, 'name' => $customer->name]],
                            'subject' => 'Welcome to FrontStore! Here is your 10% Discount 🎉',
                        ]
                    ],
                    'from' => [
                        'email' => 'mangukiyabhavdeep007@gmail.com',
                        'name' => 'FrontStore Team',
                    ],
                    'content' => [
                        ['type' => 'text/html', 'value' => $html],
                    ],
                ]);

            if ($response->successful()) {
                \Log::info('Welcome email sent to ' . $customer->email . ' via SendGrid');
            } else {
                \Log::error('SendGrid email failed for ' . $customer->email . ': ' . $response->body());
            }
        } catch (\Exception $e) {
            \Log::error('Welcome email exception for ' . $customer->email . ': ' . $e->getMessage());
        }
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
