<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userabilitytest extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['user_id', 'abilitytest_id', 'points'];

    public $timestamps = false;

    public function userabilityuserid()
    {
        return $this->belongsTo('App\User');
    }

    public function userabilitytestid()
    {
        return $this->belongsTo('App\Abilitytest');
    }
}
