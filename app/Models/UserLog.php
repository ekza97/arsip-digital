<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'ip_address',
        'user_agent',
        'details'
    ];

    // Relasi ke User (Log ini milik siapa?)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
