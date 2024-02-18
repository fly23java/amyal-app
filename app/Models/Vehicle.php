<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'owner_name',
        'sequence_number',
        'plate',
        'right_letter',
        'middle_letter',
        'left_letter',
        'plate_type',
        'vehicle_type_id',
        'account_id',
    ];

    public function type()
    {
        return $this->belongsTo(VehicleType::class,'vehicle_type_id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
    public function Driver()
    {
        return $this->hasOne(Driver::class);
    }


    
}
