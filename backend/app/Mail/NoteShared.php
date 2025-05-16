<?php
namespace App\Mail;

use App\Models\Note;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Mail\MailMessage;

class NoteShared extends Mailable
{
    use Queueable, SerializesModels;

    public $note;
    public $permission;
    public $ownerName;

    public function __construct(Note $note, string $permission)
    {
        $this->note = $note;
        $this->permission = $permission;
        $this->ownerName = $note->user ? ($note->user->display_name ?? 'A user') : 'A user';
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'A Note Has Been Shared With You',
            from: config('mail.from.address', 'noreply@notemanagementapp.com')
        );
    }

    public function content()
    {
        $appUrl = config('app.url', 'http://localhost:8000');
        $appName = config('app.name', 'Note Management App');
        $noteTitle = $this->note->title ?? 'Untitled Note';

        Log::info('Preparing NoteShared email', [
            'note_id' => $this->note->id,
            'owner_name' => $this->ownerName,
            'permission' => $this->permission,
            'app_url' => $appUrl,
            'app_name' => $appName,
        ]);

        return (new MailMessage)
            ->subject('A Note Has Been Shared With You')
            ->line('Hello,')
            ->line("{$this->ownerName} has shared a note titled **{$noteTitle}** with you.")
            ->line('**Permission**: ' . ucfirst($this->permission))
            ->action('View Note', $appUrl . '/notes/' . $this->note->id)
            ->line('If you don\'t have an account, please register at ' . $appUrl . '.')
            ->line('Thank you, ' . $appName);
    }
}