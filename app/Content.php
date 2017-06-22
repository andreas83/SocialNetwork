<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Content extends Model
{
    
    /**
     * Get the User that owns the Content.
     */
    public function user()
    {
        return $this->belongsTo('App\User', "users", "id", "user_id");
    }
}

