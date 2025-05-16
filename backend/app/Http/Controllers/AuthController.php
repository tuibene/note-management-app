<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'email' => $user->email,
                'displayName' => $user->display_name,
                'avatar' => $user->avatar,
                'email_verified_at' => $user->email_verified_at,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }

    public function sendEmailVerification(Request $request)
    {
        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        try {
            $user->sendEmailVerificationNotification();
            return response()->json(['message' => 'Verification link sent']);
        } catch (\Exception $e) {
            Log::error('Failed to send email verification', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
            return response()->json(['message' => 'Unable to send verification link', 'error' => $e->getMessage()], 500);
        }
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users',
            'display_name' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = User::create([
            'email' => $request->email,
            'display_name' => $request->display_name,
            'password' => Hash::make($request->password),
        ]);

        $user->sendEmailVerificationNotification();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                'email' => $user->email,
                'displayName' => $user->display_name,
                'avatar' => $user->avatar,
                'email_verified_at' => $user->email_verified_at,
            ],
        ]);
    }

    public function user(Request $request)
    {
        $user = $request->user();
        return response()->json([
            'email' => $user->email,
            'displayName' => $user->display_name,
            'avatar' => $user->avatar,
            'email_verified_at' => $user->email_verified_at,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'display_name' => 'required',
            'avatar' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = asset('storage/' . $path);
        }

        $user->display_name = $request->display_name;
        $user->save();

        return response()->json([
            'email' => $user->email,
            'displayName' => $user->display_name,
            'avatar' => $user->avatar,
        ]);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $user = $request->user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Current password is incorrect'], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json(['message' => 'Password changed successfully']);
    }

    public function resetRequest(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        // Find the user manually for logging purposes
        $user = User::where('email', $request->email)->first();
        if (!$user) {
            Log::info('Password reset attempt for non-existent email', [
                'email' => $request->email,
            ]);
            return response()->json(['message' => 'We can\'t find a user with that email address.'], 404);
        }

        // Send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Log the attempt
        Log::info('Password reset attempt', [
            'email' => $request->email,
            'status' => $status,
        ]);

        return $status === Password::RESET_LINK_SENT
            ? response()->json(['message' => 'Reset link sent'])
            : response()->json(['message' => 'Unable to send reset link', 'status' => $status], 400);
    }

    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->setRememberToken(Str::random(60));
                $user->save();
                event(new PasswordReset($user));
            }
        );

        Log::info('Password reset attempt', [
            'email' => $request->email,
            'token' => $request->token,
            'status' => $status,
        ]);

        return $status === Password::PASSWORD_RESET
            ? response()->json(['message' => 'Password reset successfully'])
            : response()->json(['message' => 'Unable to reset password', 'status' => $status], 400);
    }

    public function preferences(Request $request)
    {
        $user = $request->user();
        return response()->json($user->preferences ?? []);
    }

    public function updatePreferences(Request $request)
    {
        $request->validate([
            'font_size' => 'string',
            'note_color' => 'string',
            'theme' => 'in:light,dark',
        ]);
        $user = $request->user();
        $user->preferences()->updateOrCreate([], $request->all());
        return response()->json(['message' => 'Preferences updated']);
    }

    // Add method to show the reset password form
    public function showResetForm(Request $request, $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email'),
        ]);
    }
}