<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heritage extends Model
{
    protected $fillable = [
        'name',
        'description',
        'user_id',
        'category_id',
        'address_id',
    ];

    use HasFactory;
}
