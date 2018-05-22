<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class Order extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;

    protected $fillable = [
        'user_id',
        'contact_id',
        'status',
        'period',
        'delivery_time'
    ];

    public function contact()
    {
        return $this->hasOne('App\Contact');
    }

    public function user()
    {
        return $this->belongsTo('App\User');
    }


}
