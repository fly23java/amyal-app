<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Shipment extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'shipments';

    /**
    * The database primary key value.
    *
    * @var string
    */
 


    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = [
                  'serial_number',
                  'account_id',
                  'loading_city_id',
                  'unloading_city_id',
                  'loading_location',
                  'unloading_location',
                  'vehicle_type_id',
                  'goods_id',
                  'price',
                  'carrier_price',
                  'supervisor_user_id',
                  'status_id',
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
     * Get the User for this model.
     *
     * @return App\Models\User
     */
    public function Account()
    {
        return $this->belongsTo('App\Models\Account','account_id','id');
    }
    public function User()
    {
        return $this->belongsTo('App\Models\User','supervisor_user_id','id');
    }

    public function getAccountName($data)
    {
        return Account::where('id',$data)->first();
    }
    public function getAUserName($data)
    {
        return User::where('id',$data)->first();
    }
    public function getCityName($data)
    {
        return City::where('id',$data)->first();
    }
    public function getVehicle($data)
    {
        return Vehicle::findOrFail($data);

    }
    public function getCarrir($data)
    {
        $shipmentDeliveryDetail =  shipmentDeliveryDetail::where('shipment_id',$data)->first();
        $Vehicle =  Vehicle::where('id',$shipmentDeliveryDetail['vehicle_id'])->first();
        return Account::where('id', $Vehicle['account_id'])->first();
        // return Vehicle::where('id',$shipmentDeliveryDetail['vehicle_id'])->first();
    }

   
    /**
     * Get the City for this model.
     *
     * @return App\Models\City
     */
    public function City()
    {
        return $this->belongsTo('App\Models\City','unloading_city_id','id');
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
    public function Goods()
    {
        return $this->belongsTo('App\Models\Goods','goods_id','id');
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
     * Get the shipmentDeliveryDetail for this model.
     *
     * @return App\Models\ShipmentDeliveryDetail
     */
    public function shipmentDeliveryDetail()
    {
        return $this->hasOne('App\Models\ShipmentDeliveryDetail','shipment_id','id');
    }


    /**
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d g:i A');
    }

    // /**
    //  * Get updated_at in array format
    //  *
    //  * @param  string  $value
    //  * @return array
    //  */
    // public function getUpdatedAtAttribute($value)
    // {
    //     return  Carbon::createFromFormat($this->getDateFormat(), $value)->format('Y-m-d g:i A');
    // }



}
