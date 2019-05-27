<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    use RecordActivity;

    protected $fillable = [
        'favoritable_id', 'favoritable_type', 'user_id'
    ];


    protected static function boot()
    {
        parent::boot();

        static::deleting(function($favorite){
            return $favorite->activity()->delete();
        });
    }


    /**
     * Get all of the owning favoritable models.
     */
    public function favoritable()
    {
        return $this->morphTo();
    }
}
