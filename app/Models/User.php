<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

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
                  'name',
                  'email',
                  'password',
                  'birth_date',
                  'account_id',
                  'type',
                  'status'
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
     * Set the email_verified_at.
     *
     * @param  string  $value
     * @return void
     */
    // public function setEmailVerifiedAtAttribute($value)
    // {
    //     $this->attributes['email_verified_at'] = !empty($value) ? \DateTime::createFromFormat('j/n/Y g:i A', $value) : null;
    // }

    // /**
    //  * Get email_verified_at in array format
    //  *
    //  * @param  string  $value
    //  * @return array
    //  */
    // public function getEmailVerifiedAtAttribute($value)
    // {
    //     return \DateTime::createFromFormat($this->getDateFormat(), $value)->format('j/n/Y g:i A');
    // }

    // /**
    //  * Get created_at in array format
    //  *
    //  * @param  string  $value
    //  * @return array
    //  */
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
