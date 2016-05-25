<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssuePack extends Model
{
    /**
     *  Return the user who created the pack
     */
    public function user () {
      return $this->belongsTo('App\User');
    }

    /**
     * Return issues for pack
     * @return App\Issue
     */
    public function issues () {
      return $this->belongsToMany('App\Issue')->withTimestamps();
    }
}
