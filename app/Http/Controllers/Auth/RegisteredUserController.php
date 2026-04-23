<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\RegistrationCode;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. VALIDATION
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'registration_code' => ['required', 'string'],
        ]);

        // 2. CHECK CODE
        $registrationCode = RegistrationCode::where('code', $request->registration_code)->first();

        if (!$registrationCode) {
            return back()->withErrors([
                'registration_code' => 'Invalid registration code.'
            ])->withInput();
        }

        if ($registrationCode->is_used) {
            return back()->withErrors([
                'registration_code' => 'This code has already been used.'
            ])->withInput();
        }

        // 3. CREATE USER
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 4. MARK CODE AS USED
        $registrationCode->update([
            'is_used' => true,
            'used_at' => now(),
            'used_by' => $user->id,
        ]);

        // 5. LOGIN + EVENT
        event(new Registered($user));
        Auth::login($user);

        return redirect(route('dashboard', absolute: false));
    }
}