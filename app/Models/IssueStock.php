<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IssueStock extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id',
        'total_issued_male',
        'total_issued_female',
        'total_issued_pups', 
        'total_homo_male',
        'total_homo_female',
        'total_homo_pups',
        'total_hetro_male',
        'total_hetro_female',
        'total_hetro_pups',       
        'issued_male',
        'issued_female',
        'issued_pups',
        'total',
        'homo_male',
        'homo_female',
        'hetro_male',
        'hetro_female',
        'wild_male',
        'wild_female',
        'remarks'
    ];
}
