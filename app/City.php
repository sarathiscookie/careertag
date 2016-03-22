<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = [];

    protected $table = 'cities';

}
