<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conservation extends Model
{
    protected $fillable = [
        'heritage_id',
        'title',
        'content'
    ];
    
    use HasFactory;
}
