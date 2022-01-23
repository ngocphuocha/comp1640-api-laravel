<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'phone_number',
        'address'
    ];

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        $this->belongsTo(User::class);
    }
}
