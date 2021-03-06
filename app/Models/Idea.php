<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Idea extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'department_id', 'content', 'user_id', 'category_id', 'file_id', 'is_active', 'is_hidden'
    ];
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_hidden' => 'boolean',
        'is_active' => 'boolean'
    ];

    /**
     * Get the user that owns the idea.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function ideaLikes()
    {
        return $this->hasMany(IdeaLike::class);
    }

    /*
     * Get a file belong to a idea
     */
    public function file()
    {
        return $this->hasOne(File::class);
    }

    /*
     * Get a department that own a idea
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
