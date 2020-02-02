<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array
   */
  protected $fillable = [
      'user_id', 'html_content', 'json_content', 'anonymous'
  ];

  /**
   * Get the user that owns the phone.
   */
  public function user()
  {
      return $this->belongsTo('App\User');
  }
}
