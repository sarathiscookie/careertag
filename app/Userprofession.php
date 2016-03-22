<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Userprofession extends Model
{
    // define which attributes are mass assignable (for security)
    protected $fillable = ['user_id', 'graduation_id', 'experience_id', 'grade'];

    public $timestamps = false;

    protected $table = 'userprofessions';

    public function userprofessionuserid()
    {
        return $this->belongsTo('App\User');
    }

    public function userprofessiongarduationid()
    {
        return $this->belongsTo('App\Graduation');
    }

    public function userprofessionexperienceid()
    {
        return $this->belongsTo('App\Experience');
    }
}
