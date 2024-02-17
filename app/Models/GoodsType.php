<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodsType extends Model
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'goods_types';

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
     * Get the ParentGoodsType for this model.
     *
     * @return App\Models\GoodsType
     */
    public function ParentGoodsType()
    {
        return $this->belongsTo('App\Models\GoodsType','parent_id','id');
    }

    /**
     * Get the good for this model.
     *
     * @return App\Models\Good
     */
    public function good()
    {
        return $this->hasOne('App\Models\Good','goods_type_id','id');
    }

    /**
     * Get the childGoodsType for this model.
     *
     * @return App\Models\GoodsType
     */
    public function childGoodsType()
    {
        return $this->hasOne('App\Models\GoodsType','parent_id','id');
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
