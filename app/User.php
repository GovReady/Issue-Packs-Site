<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * Auto-loading relationships
     */
    protected $with = [
      'connections'
    ];

    /**
     *  Return issue packs owned by this user
     */
    public function issue_packs () {
      return $this->hasMany('App\IssuePack');
    }

    /**
     * Return User's connections
     */
    public function connections () {
      return $this->hasMany('App\Connections');
    }
}
