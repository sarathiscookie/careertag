<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userexperience extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['user_id','title','years','company','city','state','country','postal','search_string'];
    public  $timestamps = false;

    protected $table = 'userexperiences';

    public function userexperienceuserid()
    {
        return $this->belongsTo('App\User');
    }

}
