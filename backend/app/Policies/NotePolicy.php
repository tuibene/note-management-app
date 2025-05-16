<?php
namespace App\Policies;

use App\Models\Note;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Support\Facades\Log;

class NotePolicy
{
    use HandlesAuthorization;

    public function update(User $user, Note $note)
    {
        Log::info('Checking update authorization', [
            'user_id' => $user->id,
            'note_id' => $note->id,
            'note_user_id' => $note->user_id,
        ]);
        $canUpdate = $user->id === $note->user_id;
        if (!$canUpdate) {
            Log::warning('Update authorization failed', [
                'user_id' => $user->id,
                'note_id' => $note->id,
                'note_user_id' => $note->user_id,
            ]);
        }
        return $canUpdate;
    }

    public function delete(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }

    public function view(User $user, Note $note)
    {
        return $user->id === $note->user_id;
    }
}