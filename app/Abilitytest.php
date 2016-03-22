<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Abilitytest extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['target', 'question_de', 'question_en'];
}
