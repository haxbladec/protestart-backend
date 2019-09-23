<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Art extends Model
{
    //
    protected $fillable = [
        "title", "caption", "file"
    ];

    protected $hidden = [
        'user_id'
    ];

    protected $with = ['author'];

    public function getCreatedAtAttribute($value)
    {
        return strtotime($value);;
    }

    public function getUpdatedAtAttribute($value)
    {
        return strtotime($value);
    }

    public function author()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
