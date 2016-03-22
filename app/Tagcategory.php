<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tagcategory extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = [];

    protected $table = 'tagcategories';

    /*public function tagcategory()
    {
        return $this->belongsTo('App\Tag');
    }*/

}
