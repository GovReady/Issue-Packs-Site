<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Connection extends Model
{
  /**
   * Define mass-assignable attributes
   */
  protected $fillable = ['user_id', 'provider'];

  /**
   * Return User that owns this connection
   */
  public function user() {
    return $this->belongsTo('App\User');
  }
}
