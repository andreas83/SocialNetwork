<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GroupMembers extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
    'group_id', 'user_id', 'status', 'is_moderator',
  ];

    /**
     * Get the user.
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }
}
