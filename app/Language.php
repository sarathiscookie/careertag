<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = [];

    protected $table = 'languages';

}
