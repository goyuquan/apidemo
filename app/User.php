<?php

namespace App;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
    use Authenticatable, Authorizable;


    protected $fillable = [
        'phone', 'password', 'name', 'role'
    ];

    protected $hidden = [
        'contact_id', 'password', 'remember_token'
    ];

    public function contacts()
    {
        return $this->hasMany('App\Contact');
    }
}
