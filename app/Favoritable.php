<?php

namespace App;


use App\Events\ItemWasFavorite;
use App\Notifications\ReplyWasFavorite;

trait Favoritable
{

    protected static function bootFavoritable()
    {
        static::deleting(function($model){
            $model->favorites->each->delete();
        });
    }


    /**
     * Get all of the reply's favorites.
     */
    public function favorites()
    {
        return $this->morphMany('App\Favorite', 'favoritable');
    }


    /**
     * A user can favorite a reply
     */
    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if (! $this->favorites()->where($attributes)->exists()) {

            $this->favorites()->create($attributes);

            // Prepare a notification for the reply owner
            $this->owner->notify(new ReplyWasFavorite($this, $user = auth()->user()->name));

            event(new ItemWasFavorite($this));



        }
    }


    public function unFavorite()
    {
        $attributes = ['user_id' => auth()->id()];

        if($this->favorites()->where($attributes)->exists()){
            $this->favorites()->where($attributes)->each(function($favorite){
                return $favorite->delete();
            });
        }
    }


    /**
     * Check if a user favorite a reply
     */
    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }


    /**
     * Get the number
     */
    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }
}