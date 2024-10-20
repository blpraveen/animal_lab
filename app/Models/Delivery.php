<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delivery extends Model
{
    use HasFactory;
    protected $fillable = [
        'breeding_id',
        'date_of_delivery',
        'cage_no',
        'delivery_females',
        'pups_born',
        'pups_in_stock',
        'pups_issued',
        'remarks',
    ];
    public function breeding()
    {
        return $this->hasOne(Breeding::class,'id','breeding_id');
    }

    public function weaning() 
    {
        //return $this->belongsTo('App\Models\Weaning');
        return $this->hasOne(Weaning::class,'delivery_id','id');
    }

    public function weaning_mutant() 
    {
        return $this->hasOne(WeaningMutant::class,'delivery_id','id');
    }
}
