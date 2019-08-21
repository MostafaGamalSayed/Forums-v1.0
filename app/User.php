<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use Notifiable;


    protected $fillable = [
        'name', 'email', 'password'
    ];


    protected $hidden = [
        'password', 'remember_token', 'email'
    ];


    /**
     * Customizing the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'name';
    }


    /*
     * A user can has many threads
     */
    public function threads()
    {
        return $this->hasMany('App\Thread');
    }




    /*
     * A user can has many replies
     */
    public function replies()
    {
        return $this->hasMany('App\Reply');
    }


    public function activities()
    {
        return $this->hasMany('App\Activity');
    }


    /*
     * A user may add a thread
     */
    public function addThread($thread)
    {
        return $this->threads()->create($thread);
    }


    public function getUserFavoritesCount()
    {
        return Favorite::where('user_id', $this->id)->count();
    }


    /**
     * Get the activity feeds of a user
     *
     * @return mixed
     */
    public function getUserActivityFeed()
    {
       return $this->activities()
                ->take(50)
                ->latest()
                ->with('subject')
                ->get()
                ->reject(function($activity){
                    return !starts_with($activity->type, 'created');
                })
                ->groupBy(function($activity){
                    return $activity->created_at->format('y-m-d');
                });
    }


    /**
     * Record that the user has read the given thread
     *
     * @param Thread $thread
     */
     public function read($thread)
     {
       cache()->forever(
         $this->getCacheKeyForVisitedThread($thread),
         Carbon::now()
       );
     }


     /**
     * Get the cache key for when a user read a thread
     *
     * @param Thread $thread
     */
     public function getCacheKeyForVisitedThread($thread)
     {
       $key = sprintf("users.%s.visits.%s", $this->id, $thread->id);
       return $key;
     }


     /**
     * Get the user last reply
     */
     public function lastReply()
     {
       return $this->hasOne('App\Reply')->latest();
     }
}
