<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('products.index');
        }

        return view('admin.login');
    }


    public function authenticate(Request $request)
    {
        if (Auth::attempt($request->only('username', 'password'))) {
            return redirect()->route('products.index');
        }

        return back()->withErrors(['login_failed' => 'Invalid credentials']);
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('transactions.index');
    }
}

