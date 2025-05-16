Hello,

{{ $ownerName }} has shared a note titled "**{{ $noteTitle }}**" with you.

**Permission**: {{ ucfirst($permission) }}

[View Note]({{ $noteUrl }})

If you don't have an account, please register at {{ env('APP_URL') }} to access the note.

Thank you,
{{ config('app.name') }}