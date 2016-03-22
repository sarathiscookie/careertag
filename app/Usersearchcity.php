<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Usersearchcity extends Model
{
    protected $fillable = ['user_id','search_city','search_state','search_country','search_postal','search_string'];
    public  $timestamps = false;

    protected $table = 'user_search_cities';

    public function usersearchcityuserid()
    {
        return $this->belongsTo('App\User');
    }
}
