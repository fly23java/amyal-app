<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShipmentDeliveryDetail extends Model
{
    
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shipment_delivery_details';

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
                  'shipment_id',
                  'vehicle_id',
                  'loading_time',
                  'unloading_time',
                  'arrival_time',
                  'departure_time',
                  'delivery_status',
                  'delivery_document'
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
     * Get the Shipment for this model.
     *
     * @return App\Models\Shipment
     */
    public function Shipment()
    {
        return $this->belongsTo('App\Models\Shipment','shipment_id','id');
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
     * Set the loading_time.
     *
     * @param  string  $value
     * @return void
     */
    public function setLoadingTimeAttribute($value)
    {
        $this->attributes['loading_time'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    }

    /**
     * Set the unloading_time.
     *
     * @param  string  $value
     * @return void
     */
    public function setUnloadingTimeAttribute($value)
    {
        $this->attributes['unloading_time'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    }

    /**
     * Set the arrival_time.
     *
     * @param  string  $value
     * @return void
     */
    public function setArrivalTimeAttribute($value)
    {
        $this->attributes['arrival_time'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    }

    /**
     * Set the departure_time.
     *
     * @param  string  $value
     * @return void
     */
    public function setDepartureTimeAttribute($value)
    {
        $this->attributes['departure_time'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    }

    /**
     * Get loading_time in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getLoadingTimeAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get unloading_time in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getUnloadingTimeAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get arrival_time in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getArrivalTimeAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    }

    /**
     * Get departure_time in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getDepartureTimeAttribute($value)
    {
        return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
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
