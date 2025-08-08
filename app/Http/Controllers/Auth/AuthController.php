<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showSignup()
    {
        return view('auth.signup');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only(['email', 'password']);
        
        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Redirect based on user role
            if (Auth::user()->isAdmin()) {
                return redirect()->intended('/admin');
            } elseif (Auth::user()->isStaff()) {
                return redirect()->intended('/staff');
            } elseif (Auth::user()->isPriest()) {
                return redirect()->intended('/priest');
            } else {
                return redirect()->intended('/');
            }
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->withInput($request->only('email'))
          ->with('error', 'Invalid email or password. Please try again.');
    }

    public function signup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
            'email_verification_token' => Str::random(64),
        ]);

        // Send verification email
        $this->sendVerificationEmail($user);

        Auth::login($user);

        return redirect()->route('verification.notice')->with('success', 'Account created successfully! Please check your email to verify your account.');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function verifyEmail($token)
    {
        $user = User::where('email_verification_token', $token)->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid verification token.');
        }

        // Check if already verified
        if ($user->email_verified_at) {
            return redirect()->route('login')->with('success', 'Email is already verified! You can now log in.');
        }

        $user->update([
            'email_verified_at' => now(),
            'email_verification_token' => null,
        ]);

        return redirect()->route('login')->with('success', 'Email verified successfully! You can now log in.');
    }

    public function resendVerification(Request $request)
    {
        try {
            $request->validate([
                'email' => 'required|email|exists:users,email',
            ]);

            $user = User::where('email', $request->email)->first();

            if ($user->email_verified_at) {
                return response()->json(['success' => false, 'message' => 'Email is already verified.'], 400);
            }

            $user->update([
                'email_verification_token' => Str::random(64),
            ]);

            $this->sendVerificationEmail($user);

            return response()->json(['success' => true, 'message' => 'Verification email sent successfully!']);
        } catch (\Exception $e) {
            \Log::error('Email verification error: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Error sending verification email. Please try again.'], 500);
        }
    }

    private function sendVerificationEmail($user)
    {
        $verificationUrl = route('verification.verify', $user->email_verification_token);

        Mail::send('emails.verify', [
            'user' => $user,
            'verificationUrl' => $verificationUrl,
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Verify Your Email - Parish CMS');
        });
    }

    public function showVerificationNotice()
    {
        return view('auth.verify');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();
        $token = Str::random(64);

        $user->update([
            'password_reset_token' => $token,
            'password_reset_expires_at' => now()->addHours(1),
        ]);

        Mail::send('emails.reset-password', [
            'user' => $user,
            'resetUrl' => route('password.reset', $token),
        ], function ($message) use ($user) {
            $message->to($user->email)
                    ->subject('Reset Your Password - Parish CMS');
        });

        return back()->with('success', 'Password reset link sent to your email!');
    }

    public function showResetPassword($token)
    {
        $user = User::where('password_reset_token', $token)
                    ->where('password_reset_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired reset token.');
        }

        return view('auth.reset-password', compact('token'));
    }

    public function resetPassword(Request $request, $token)
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::where('password_reset_token', $token)
                    ->where('password_reset_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return redirect()->route('login')->with('error', 'Invalid or expired reset token.');
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
        ]);

        return redirect()->route('login')->with('success', 'Password reset successfully! You can now log in.');
    }
} 