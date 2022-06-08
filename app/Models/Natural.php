<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Natural extends Model
{
    protected $fillable = [
        'heritage_id',
        'category',
        'classification',
        'sub_category',
        'area',
        'ownership',
        'other_name',
        'scientific_name',
        'class_origin',
        'habitat',
        'site_collected',
        'seasonability',
        'special_note',
        'legislation',
    ];

    use HasFactory;
}
