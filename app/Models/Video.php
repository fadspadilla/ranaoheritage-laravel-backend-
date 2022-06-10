<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    protected $fillable = [
        'path',
        'cloud_id',
        'heritage_id',
    ];
    
    use HasFactory;
}
