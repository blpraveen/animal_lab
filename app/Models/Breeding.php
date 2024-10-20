<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Breeding extends Model
{
    protected $fillable = [
        'room_id',
        'strain_id',
        'colony_id',
        'date_of_ifm',
        'breeder_male',
        'breeder_female'
    ];
}
