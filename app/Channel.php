<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{


    protected $fillable = [
        'name', 'slug'
    ];


    /*
     * Customizing the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


   /*
    * A channel can has many threads
    */
    public function threads()
    {
        return $this->hasMany('App\Thread');
    }
}
