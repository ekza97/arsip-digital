<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FiscalYear extends Model
{
    use HasFactory;

    protected $fillable = [
        'year',
        'is_active'
    ];
}
