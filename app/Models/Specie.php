<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specie extends Model
{
    protected $fillable = [
        'name',
        'code',
        'reason',
    ];


    public function strains() {
        return $this->belongsTo('App\Models\Strain');
    }
}
