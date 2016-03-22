<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['tagcategory_id', 'title_de', 'title_en', 'suggestion', 'created_by'];

    protected $table = 'tags';

    public function tag()
    {
        return $this->belongsTo('App\Tagcategory');
    }
}
