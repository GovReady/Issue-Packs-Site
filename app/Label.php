<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    /**
     * Return issues with this label
     * @return type
     */
    public function issues () {
      return $this->belongsToMany('App\Issue')->withTimestamps();
    }
}
