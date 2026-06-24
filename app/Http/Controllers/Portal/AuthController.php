<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLoginForm(): View
    {
        return view('portal.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        $customer = Customer::withoutGlobalScopes()
            ->where('email', $request->email)
            ->first();

        if (!$customer || !$customer->password || !Hash::check($request->password, $customer->password)) {
            return back()
                ->withErrors(['email' => 'Invalid credentials or portal access not enabled.'])
                ->withInput($request->only('email'));
        }

        $request->session()->regenerate();
        $request->session()->put('portal_customer_id', $customer->id);
        $request->session()->put('portal_customer_name', $customer->name);

        return redirect()->route('portal.dashboard');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget(['portal_customer_id', 'portal_customer_name']);
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('portal.login');
    }
}