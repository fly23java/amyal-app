<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name_arabic',
        'name_english',
        'cr_number',
        'bank',
        'iban',
        'account_number',
        'tax_number',
        'tax_value',
        'type',
    ];

    public function users() 
    {
        return $this->hasMany(User::class);
    }
    
    public function getAdminUser()
    {
        return $this->users()->where('type','admin')->first();
    }
    

    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function drivers()
    {
        return $this->hasMany(Driver::class);
    }

}
