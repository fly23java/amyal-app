<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehicleType extends Model
{
    use HasFactory;
    protected $fillable = [
        'name_arabic',
        'name_english',
    ];


    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function goodsTypes()
    {
        return $this->belongsToMany(GoodsType::class);
    }

    
}
