<?php
namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\SharedNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Events\NoteUpdated;
use Illuminate\Support\Facades\Mail;
use App\Mail\NoteShared;
use Illuminate\Support\Facades\Log;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->user()->notes()->with('labels');
        if ($request->has('label_id')) {
            $query->whereHas('labels', function ($q) use ($request) {
                $q->where('id', $request->label_id);
            });
        }
        $notes = $query->orderBy('pinned', 'desc')
                      ->orderBy('pinned_at', 'desc')
                      ->get();
        return response()->json($notes);
    }

    public function search(Request $request)
    {
        $keyword = $request->query('keyword');
        $notes = $request->user()->notes()
            ->where('title', 'like', "%$keyword%")
            ->orWhere('content', 'like', "%$keyword%")
            ->with('labels')
            ->get();
        return response()->json($notes);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'content' => 'required',
            'images' => 'nullable|array',
            'labels' => 'nullable|array',
        ]);

        $note = $request->user()->notes()->create([
            'title' => $request->title,
            'content' => $request->content,
            'pinned' => $request->pinned ?? false,
            'pinned_at' => $request->pinned ? now() : null,
            'is_locked' => $request->is_locked ?? false,
            'password' => $request->is_locked ? Hash::make($request->password) : null,
            'images' => $request->images,
        ]);

        if ($request->labels) {
            $labels = $request->user()->labels()->whereIn('name', $request->labels)->pluck('id');
            $note->labels()->sync($labels);
        }

        return response()->json($note->load('labels'), 201);
    }

    public function update(Request $request, Note $note)
    {
        Log::info('Attempting to update note', [
            'note_id' => $note->id,
            'user_id' => $request->user()->id,
        ]);
        $this->authorize('update', $note);

        try {
            $request->validate([
                'title' => 'required',
                'content' => 'required',
                'images' => 'nullable|array',
                'labels' => 'nullable|array',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }

        try {
            $note->update([
                'title' => $request->title,
                'content' => $request->content,
                'pinned' => $request->pinned ?? false,
                'pinned_at' => $request->pinned ? ($note->pinned_at ?? now()) : null,
                'images' => $request->images,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to update note', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to update note', 'error' => $e->getMessage()], 500);
        }

        if ($request->labels) {
            try {
                $labels = $request->user()->labels()->whereIn('name', $request->labels)->pluck('id');
                $note->labels()->sync($labels);
            } catch (\Exception $e) {
                Log::error('Failed to sync labels', ['error' => $e->getMessage()]);
                return response()->json(['message' => 'Failed to sync labels', 'error' => $e->getMessage()], 500);
            }
        }

        try {
            broadcast(new NoteUpdated($note->load('labels')))->toOthers();
        } catch (\Exception $e) {
            Log::warning('Broadcasting failed', ['error' => $e->getMessage()]);
        }

        return response()->json($note->load('labels'));
    }

    public function destroy(Request $request, Note $note)
    {
        $this->authorize('delete', $note);
        $note->delete();
        return response()->json(['message' => 'Note deleted']);
    }

public function share(Request $request, Note $note)
    {
        Log::info('Attempting to share note', [
            'note_id' => $note->id,
            'user_id' => $request->user()->id,
            'emails' => $request->emails,
            'permission' => $request->permission,
            'has_user' => $note->user ? true : false,
            'mail_config' => [
                'mailer' => config('mail.mailer'),
                'host' => config('mail.host'),
                'port' => config('mail.port'),
                'from_address' => config('mail.from.address'),
                'username' => config('mail.username'),
            ],
        ]);
        $this->authorize('update', $note);

        try {
            $request->validate([
                'emails' => 'required|array|min:1',
                'emails.*' => 'required|email',
                'permission' => 'required|in:read,edit',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        }

        try {
            $sharedWith = [];
            foreach ($request->emails as $email) {
                $sharedNote = SharedNote::create([
                    'note_id' => $note->id,
                    'user_id' => $request->user()->id,
                    'shared_with_email' => $email,
                    'permission' => $request->permission,
                ]);
                try {
                    Mail::to($email)->send(new NoteShared($note, $request->permission));
                    $status = 'email_sent';
                    Log::info('Email sent successfully', ['email' => $email, 'note_id' => $note->id]);
                } catch (\Exception $e) {
                    Log::error('Failed to send share email', [
                        'email' => $email,
                        'note_id' => $note->id,
                        'error' => $e->getMessage(),
                        'trace' => $e->getTraceAsString(),
                    ]);
                    $status = 'email_failed';
                }
                $sharedWith[] = [
                    'email' => $email,
                    'permission' => $request->permission,
                    'status' => $status,
                ];
            }

            Log::info('Note shared successfully', ['shared_with' => $sharedWith]);
            return response()->json([
                'message' => 'Note shared successfully',
                'shared_with' => $sharedWith,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to share note', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return response()->json(['message' => 'Failed to share note', 'error' => $e->getMessage()], 500);
        }
    }

    public function revokeShare(Request $request, Note $note)
    {
        Log::info('Attempting to revoke share', [
            'note_id' => $note->id,
            'user_id' => $request->user()->id,
            'email' => $request->email,
        ]);
        $this->authorize('update', $note);

        try {
            $request->validate([
                'email' => 'nullable|email',
            ]);

            $query = SharedNote::where('note_id', $note->id);
            if ($request->has('email')) {
                $query->where('shared_with_email', $request->email);
            }

            $deleted = $query->delete();
            Log::info('Share revoked successfully', [
                'note_id' => $note->id,
                'deleted_count' => $deleted,
            ]);

            return response()->json(['message' => 'Share revoked successfully', 'deleted_count' => $deleted]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json(['message' => 'Validation failed', 'errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Failed to revoke share', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Failed to revoke share', 'error' => $e->getMessage()], 500);
        }
    }

    public function sharedNotes(Request $request)
    {
        $sharedNotes = SharedNote::where('shared_with_email', $request->user()->email)
            ->with(['note' => function ($query) {
                $query->with('labels');
            }, 'user'])
            ->get()
            ->map(function ($sharedNote) {
                return [
                    'id' => $sharedNote->note->id,
                    'title' => $sharedNote->note->title,
                    'content' => $sharedNote->note->content,
                    'images' => $sharedNote->note->images,
                    'shared_by' => $sharedNote->user->display_name,
                    'shared_at' => $sharedNote->shared_at,
                    'permission' => $sharedNote->permission,
                ];
            });

        return response()->json($sharedNotes);
    }

    public function verifyPassword(Request $request, Note $note)
    {
        $this->authorize('view', $note);

        $request->validate(['password' => 'required']);

        $verified = $note->is_locked && Hash::check($request->password, $note->password);
        return response()->json(['verified' => $verified]);
    }

    public function updatePassword(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $request->validate(['password' => 'required|confirmed']);

        $note->update([
            'password' => Hash::make($request->password),
            'is_locked' => true,
        ]);

        return response()->json(['message' => 'Password updated']);
    }

    public function deletePassword(Request $request, Note $note)
    {
        $this->authorize('update', $note);

        $note->update([
            'password' => null,
            'is_locked' => false,
        ]);

        return response()->json(['message' => 'Password disabled']);
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'images.*' => 'required|image|max:2048',
        ]);

        $urls = [];
        foreach ($request->file('images') as $image) {
            $path = $image->store('note_images', 'public');
            $urls[] = asset('storage/' . $path);
        }

        return response()->json(['urls' => $urls]);
    }

    public function uploadImagesForNote(Request $request, Note $note)
    {
        $this->authorize('update', $note);
        $request->validate([
            'note_id' => 'required|exists:notes,id',
            'images.*' => 'required|image|max:2048'
        ]);

        $currentImages = $note->images ?? [];
        $newImages = [];

        foreach ($request->file('images') as $image) {
            $path = $image->store('notes/' . $note->id, 'public');
            $newImages[] = $path;
        }

        $updatedImages = array_merge($currentImages, $newImages);
        $note->update(['images' => $updatedImages]);

        $urls = array_map(function ($path) {
            return asset('storage/' . $path);
        }, $newImages);

        return response()->json(['message' => 'Images uploaded for note', 'urls' => $urls]);
    }
}