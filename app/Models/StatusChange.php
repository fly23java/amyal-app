<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StatusChange extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'status_changes';

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
                  'status_id',
                  'user_id'
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
     * Get the Status for this model.
     *
     * @return App\Models\Status
     */
    public function Status()
    {
        return $this->belongsTo('App\Models\Status','status_id','id');
    }

    /**
     * Get the User for this model.
     *
     * @return App\Models\User
     */
    public function User()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
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
