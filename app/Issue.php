<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    public static function boot() {
      parent::boot();

      Issue::deleting(function ($issue) {
        $issue->labels()->delete();
      });
    }

    /**
     * Return issue packs this issue belongs to
     * @return App\IssuePack
     */
    public function issue_packs () {
      return $this->belongsToMany('App\IssuePack')->withTimestamps();
    }

    /**
     * Return all labels associated with this issue
     * @return type
     */
    public function labels () {
      return $this->belongsToMany('App\Label')->withTimestamps();
    }
}
