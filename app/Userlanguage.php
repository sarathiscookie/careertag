<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userlanguage extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['user_id', 'language_id', 'ranking'];

    public $timestamps = false;

    protected $table = 'userlanguages';

    public function userlanguageuserid()
    {
        return $this->belongsTo('App\User');
    }

    public function userlanguagelangid()
    {
        return $this->belongsTo('App\Language');
    }
}
