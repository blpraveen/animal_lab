<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Room extends Model
{

    public static function boot()
     {
        parent::boot();
        static::creating(function($model)
        {
            $user = Auth::user();           
            $model->created_by = $user->id;
            $model->updated_by = $user->id;
        });
        static::updating(function($model)
        {
            $user = Auth::user();
            $model->updated_by = $user->id;
        });       
    }

    protected $fillable = [
        'room_no',
        'room_name',
        'strain_id',
        'remarks',
        'created_by',
        'updated_by'
    ];


    public function strain()
    {
        return $this->hasOne(Strain::class,'id','strain_id');
    }


}
