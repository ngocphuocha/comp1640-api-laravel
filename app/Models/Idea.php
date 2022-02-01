<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'department_id', 'content', 'user_id', 'category_id', 'file_id', 'is_active', 'is_hidden'
    ];

    /**
     * Get the user that owns the idea.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /*
     * Get a file belong to a idea
     */
    public function file()
    {
        return $this->hasOne(File::class);
    }
}
