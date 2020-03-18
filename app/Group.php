<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'name', 'description', 'avatar',
    'background', 'visibility', 'members', 'posts',
  ];

    public function content()
    {
        return $this->belongsToMany('App\Content');
    }
}
