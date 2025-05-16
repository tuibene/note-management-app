<?php
namespace App\Http\Controllers;

use App\Models\UserPreference;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PreferenceController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            $preference = $user->preference ?? UserPreference::create([
                'user_id' => $user->id,
                'font_size' => '16px',
                'note_color' => '#ffffff',
                'theme' => 'light',
            ]);
            Log::info('Preferences retrieved', ['user_id' => $user->id, 'preference' => $preference]);
            return response()->json([
                'message' => 'Preferences retrieved successfully',
                'data' => $preference,
            ]);
        } catch (\Exception $e) {
            Log::error('Preference index failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
            ]);
            return response()->json([
                'message' => 'Failed to retrieve preferences',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            Log::info('PreferenceController::store called', [
                'input' => $request->all(),
                'user_id' => Auth::id(),
            ]);
            $data = $request->validate([
                'font_size' => 'required|in:12px,14px,16px,18px,20px',
                'note_color' => ['required', 'regex:/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/'],
                'theme' => 'required|in:light,dark',
            ]);
            $user = Auth::user();
            $preference = $user->preference ?? UserPreference::create(['user_id' => $user->id]);
            $preference->update($data);
            Log::info('Preferences updated', ['user_id' => $user->id, 'preference' => $preference]);
            return response()->json([
                'message' => 'Preferences updated successfully',
                'data' => $preference,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::warning('Preference validation failed', [
                'errors' => $e->errors(),
                'user_id' => Auth::id(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            Log::error('Preference update failed', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'input' => $request->all(),
            ]);
            return response()->json([
                'message' => 'Failed to update preferences',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}