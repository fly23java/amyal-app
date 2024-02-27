<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'statuses';

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
                  'message_text_in_arabic',
                  'message_text_in_english',
                  'confirm_sending_the_message',
                  'parent_id'
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
     * Get the ParentStatus for this model.
     *
     * @return App\Models\Status
     */
    public function ParentStatus()
    {
        return $this->belongsTo('App\Models\Status','parent_id','id');
    }

    /**
     * Get the shipment for this model.
     *
     * @return App\Models\Shipment
     */
    public function shipment()
    {
        return $this->hasOne('App\Models\Shipment','status_id','id');
    }

    /**
     * Get the childStatus for this model.
     *
     * @return App\Models\Status
     */
    public function childStatus()
    {
        return $this->hasOne('App\Models\Status','parent_id','id');
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
