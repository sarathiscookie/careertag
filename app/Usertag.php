<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usertag extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['user_id', 'tag_id'];

    protected $table = 'usertags';

    public $timestamps = false;

    public function usertaguserid()
    {
        return $this->belongsTo('App\User');
    }

    public function usertagtagid()
    {
        return $this->belongsTo('App\Tag');
    }

}
