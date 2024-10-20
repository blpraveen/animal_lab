<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Weaning extends Model
{
    use HasFactory;
    protected $fillable = [
        'delivery_id',
        'weaned_male',
        'weaned_female',
        'date_of_weaned',
        'remarks',
    ];
}
