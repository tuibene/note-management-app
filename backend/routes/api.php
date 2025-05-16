<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\PreferenceController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/password/reset-request', [AuthController::class, 'resetRequest']);
Route::post('/password/reset', [AuthController::class, 'reset']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Auth and User Management
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/user/profile', [AuthController::class, 'updateProfile']);
    Route::post('/user/password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/email/verification-notification', [AuthController::class, 'sendEmailVerification']);

    // Add verification route
    Route::get('/email/verify/{id}/{hash}', function (Request $request, $id, $hash) {
        $user = \App\Models\User::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link'], 400);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email already verified'], 400);
        }

        $user->markEmailAsVerified();
        event(new \Illuminate\Auth\Events\Verified($user));

        return response()->json(['message' => 'Email verified successfully']);
    })->middleware('signed')->name('verification.verify');

    // Notes Management
    Route::get('/notes', [NoteController::class, 'index']);
    Route::post('/notes', [NoteController::class, 'store']);
    Route::put('/notes/{note}', [NoteController::class, 'update']);
    Route::delete('/notes/{note}', [NoteController::class, 'destroy']);
    Route::post('/notes/{note}/share', [NoteController::class, 'share']);
    Route::delete('/notes/{note}/share', [NoteController::class, 'revokeShare']);
    Route::get('/shared-notes', [NoteController::class, 'sharedNotes']);
    Route::post('/notes/{note}/verify-password', [NoteController::class, 'verifyPassword']);
    Route::post('/notes/{note}/update-password', [NoteController::class, 'updatePassword']);
    Route::delete('/notes/{note}/password', [NoteController::class, 'deletePassword']);
    Route::post('/upload-images', [NoteController::class, 'uploadImages']);
    Route::post('/notes/{note}/upload-images', [NoteController::class, 'uploadImagesForNote']);

    // Labels Management
    Route::get('/labels', [LabelController::class, 'index']);
    Route::post('/labels', [LabelController::class, 'store']);
    Route::put('/labels/{name}', [LabelController::class, 'update']);
    Route::delete('/labels/{name}', [LabelController::class, 'destroy']);

    // Preferences Management
    Route::get('/preferences', [PreferenceController::class, 'index']);
    Route::post('/preferences', [PreferenceController::class, 'store']);
});