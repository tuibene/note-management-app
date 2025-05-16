<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id', 'title', 'content', 'pinned', 'pinned_at',
        'is_locked', 'password', 'images', 'created_at', 'updated_at',
    ];

    protected $casts = [
        'images' => 'array',
        'pinned' => 'boolean',
        'is_locked' => 'boolean',
        'pinned_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'note_label');
    }

    public function sharedNotes()
    {
        return $this->hasMany(SharedNote::class);
    }
}