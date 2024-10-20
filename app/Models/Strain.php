<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Strain extends Model
{
    protected $fillable = [
        'name',
        'strain_code',
        'specie_id',
        'reason',
    ];


    public function specie()
    {
        return $this->hasOne(Specie::class,'id','specie_id');
    }
}
