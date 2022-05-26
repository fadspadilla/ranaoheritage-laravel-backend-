<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Movable extends Model
{
    protected $fillable = [
        'heritage_id',
        'category',
        'type',
        'type_sub',
        'date',
        'age',
        'owner',
        'acquisition',
        'religion',
        'artist',
        'nationality',
        'prev_owner',
        'curr_owner',
        'volume',
        'arrangement',
        'contact_person',
    ];

    use HasFactory;
}
