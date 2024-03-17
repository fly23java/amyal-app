<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceDetail extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'price_details';

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
                  'price_id',
                  'vehicle_type_id',
                  'goods_id',
                  'loading_city_id',
                  'dispersal_city_id',
                  'price',
                  'accepted_user_id'
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
     * Get the Price for this model.
     *
     * @return App\Models\Price
     */
    public function Price()
    {
        return $this->belongsTo('App\Models\Price','price_id','id');
    }

    /**
     * Get the VehicleType for this model.
     *
     * @return App\Models\VehicleType
     */
    public function VehicleType()
    {
        return $this->belongsTo('App\Models\VehicleType','vehicle_type_id','id');
    }

    /**
     * Get the Good for this model.
     *
     * @return App\Models\Good
     */
    public function Good()
    {
        return $this->belongsTo('App\Models\Good','goods_id','id');
    }

    /**
     * Get the City for this model.
     *
     * @return App\Models\City
     */
    public function City()
    {
        return $this->belongsTo('App\Models\City','dispersal_city_id','id');
    }

    /**
     * Get the User for this model.
     *
     * @return App\Models\User
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User','accepted_user_id','id');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get updated_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUpdatedAtAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

}
