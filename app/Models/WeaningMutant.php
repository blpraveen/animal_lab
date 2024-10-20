<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeaningMutant extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id',
        'weaned_homo_male',
        'weaned_homo_female',
        'weaned_hetro_male',
        'weaned_hetro_female',
        'weaned_wild_male',
        'weaned_wild_female',
        'date_of_weaned',
        'remarks',
    ];
}
