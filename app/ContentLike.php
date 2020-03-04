<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContentLike extends Model
{
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
      'user_id', 'content_id', 'key',
  ];

    /**
     * Get the content that owns the like.
     */
    public function content()
    {
        return $this->belongsTo('App\Content');
    }

    /**
     * Get the user that owns the like.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
