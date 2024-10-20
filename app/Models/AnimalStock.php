<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnimalStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id',
        'weaned_male',
        'weaned_female',
        'issued_male',
        'issued_female',
        'issued_pups',
        'total_issued_male',
        'total_issued_female',
        'total_issued_pups',
        'total_male',
        'total_female',
        'total_pups',
        'total'
    ];
}
