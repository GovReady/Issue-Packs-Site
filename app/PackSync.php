<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PackSync extends Model
{
    /**
     * Return pack this sync refers to
     * @return App\IssuePack
     */
    public function pack () {
      return $this->belongsTo('App\IssuePack');
    }

    /**
     * Return user who synced the pack
     * @return App\User
     */
    public function user() {
      return $this->belongsTo('App\User');
    }
}
