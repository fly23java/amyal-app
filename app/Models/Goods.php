<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'goods';

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
                  'price',
                  'photo',
                  'goods_type_id',
                  'unit_id',
                  'account_id'
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
     * Get the GoodsType for this model.
     *
     * @return App\Models\GoodsType
     */
    public function GoodsType()
    {
        return $this->belongsTo('App\Models\GoodsType','goods_type_id','id');
    }

    /**
     * Get the Unit for this model.
     *
     * @return App\Models\Unit
     */
    public function Unit()
    {
        return $this->belongsTo('App\Models\Unit','unit_id','id');
    }

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
     * Get created_at in array format
     *
     * @param  string  $value
     * @return array
     */
    // public function getCreatedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    // }

    // /**
    //  * Get updated_at in array format
    //  *
    //  * @param  string  $value
    //  * @return array
    //  */
    // public function getUpdatedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    // }

}
