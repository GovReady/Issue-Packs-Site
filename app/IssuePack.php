<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IssuePack extends Model
{
    public static function boot() {
      parent::boot();

      IssuePack::deleting(function ($issuePack) {
        $issuePack->issues()->delete();
      });
    }

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

    /**
     * Return sync records for a pack
     * @return App\PackSync
     */
    public function syncs () {
      return $this->hasMany('App\PackSync')->withTimestamps();
    }
}
