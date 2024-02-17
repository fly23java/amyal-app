<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'drivers';

    /**
    * The database primary key value.
    *
    * @var string
    */
    protected $primaryKey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'name_arabic',
                  'name_english',
                  'email',
                  'password',
                  'phone',
                  'identity_number',
                  'date_of_birth_hijri',
                  'date_of_birth_gregorian',
                  'account_id',
                  'vehicle_id'
              ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [];
    
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [];
    
    /**
     * Get the Account for this model.
     *
     * @return App\Models\Account
     */
    public function Account()
    {
        return $this->belongsTo('App\Models\Account','account_id','id');
    }

    /**
     * Get the Vehicle for this model.
     *
     * @return App\Models\Vehicle
     */
    public function Vehicle()
    {
        return $this->belongsTo('App\Models\Vehicle','vehicle_id','id');
    }

    /**
     * Set the date_of_birth_hijri.
     *
     * @param  string  $value
     * @return void
     */
    // public function setDateOfBirthHijriAttribute($value)
    // {
    //     $this->attributes['date_of_birth_hijri'] = !empty($value) ? \DateTime::createFromFormat('dY-m-d H:i:s', $value) : null;
       
    // }

    /**
     * Set the date_of_birth_gregorian.
     *
     * @param  string  $value
     * @return void
     */
    // public function setDateOfBirthGregorianAttribute($value)
    // {
    //     $this->attributes['date_of_birth_gregorian'] = !empty($value) ? \DateTime::createFromFormat('Y-m-d H:i:s', $value) : null;
    // }

    /**
     * Get date_of_birth_hijri in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getDateOfBirthHijriAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('dY-m-d H:i:s');
    // }

    /**
     * Get date_of_birth_gregorian in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getDateOfBirthGregorianAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('dY-m-d H:i:s');
    // }

    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getCreatedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('dY-m-d H:i:s g:i A');
    // }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getUpdatedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('dY-m-d H:i:s g:i A');
    // }

}
