<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Icon extends Model
{
    protected $fillable = [
        'name',
        'link',
        'cloud_id',
    ];

    use HasFactory;
}
