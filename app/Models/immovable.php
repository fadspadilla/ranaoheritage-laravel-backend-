<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class immovable extends Model
{
    protected $fillable = [
        'heritage_id',
        'category',
        'type',
        'land_area',
        'structure_area',
        'year_constructed',
        'ownership',
        'jurisdiction',
        'legislation',
    ];

    use HasFactory;
}
