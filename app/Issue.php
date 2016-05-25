<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Issue extends Model
{
    /**
     * Return issue packs this issue belongs to
     * @return App\IssuePack
     */
    public function issue_packs () {
      return $this->belongsToMany('App\IssuePack')->withTimestamps();
    }
}
