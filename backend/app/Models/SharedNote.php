<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SharedNote extends Model
{
    protected $fillable = ['note_id', 'user_id', 'shared_with_email', 'permission', 'shared_at', 'created_at', 'updated_at'];

    protected $casts = [
        'shared_at' => 'datetime',
    ];

    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}