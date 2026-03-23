<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpController extends Controller
{
    public function index()
    {
        return view('help', [
            'supportEmail' => 'support@sellercentral.com',
            'appVersion' => '1.0.0'
        ]);
    }
}