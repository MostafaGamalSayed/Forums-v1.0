<?php

namespace App;

use App\Filters\ThreadFilters;
use App\Notifications\ThreadWasUpdated;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Events\ReplyWasCreated;
use Illuminate\Support\Facades\Redis;
use App\Visit;
use Laravel\Scout\Searchable;


class Thread extends Model
{
    use RecordActivity, Searchable;


    protected $guarded = [];

    protected $with = ['channel', 'owner'];

    protected $appends = ['isSubscribed'];

    /*
     * Customizing the route key for the model
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }


    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('replyCount', function(Builder $builder){
            return $builder->withCount('replies');
        });

        // delete all the replies of a thread on deleting a thread
        static::deleting(function($thread){
            return $thread->replies->each->delete();
        });

        static::created(function ($thread) {
        $thread->update(['slug' => $thread->title]);
    });
    }

    /**
     * Set the thread's slug.
     *
     * @param  string  $value
     * @return void
     */
     public function setSlugAttribute($value)
     {
       if (static::whereSlug($slug = str_slug($value))->exists()) {
           $slug = "{$slug}-{$this->id}";
       }
        $this->attributes['slug'] = $slug;
     }

     /* Get the full path of a thread */
     public function path()
     {
        return "{$this->channel->slug}/threads/{$this->slug}";
     }

     /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        return $this->toArray() + ['path' => $this->path(), 'isSolved' => $this->isSolved(), 'owner' => $this->owner];
    }

    /**
     * A thread belongs to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('App\User', 'user_id');
    }


    /**
     * A thread can has many replies
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }


    /**
     * A thread belongs to one channel
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function channel()
    {
        return $this->belongsTo('App\Channel');
    }


    /**
     * Add a reply to a thread
     *
     * @param Reply $reply
     */
    public function addReply($reply)
    {
        $reply =  $this->replies()->create($reply);

        // Update the updated_at field of the thread
        $reply->thread->update([
          'updated_at' => Carbon::now()
        ]);

        // Prepare notifications for mentioned users
        event(new ReplyWasCreated($reply));

        // Prepare notification for the subscribed users
        $this->subscriptions
            ->filter(function($sub) use ($reply){
                return $sub->id != $reply->user_id;
            })
            ->each(function($sub) use ($reply){
                $sub->notify(new ThreadWasUpdated($this, $reply));
            });

        return $reply;
    }


    /**
     * Apply all the relevant thread filters
     *
     * @param Builder $query
     * @param ThreadFilters $filters
     * @return mixed
     */
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    /**
     * Define a Thread-user subscription (Many to many) relationship
     *
     * @return $this
     */
    public function subscriptions()
    {
        return $this->belongsToMany('App\User', 'thread_subscriptions', 'thread_id', 'user_id')
                    ->using('App\ThreadSubscription');
    }

    /**
     * A user can subscribe to a thread
     *
     * @param null $userId
     */
    public function subscribe($userId = null)
    {
        if(!$this->IsSubscribed)
        {
            $this->subscriptions()->attach($userId ? : auth()->id());
        }
    }


    /**
     * A user can un-subscribe from a thread
     *
     * @param null $userId
     */
    public function unSubscribe($userId = null)
    {
        if($this->isSubscribed)
        {
            $this->subscriptions()->detach($userId ? : auth()->id());
        }
    }

    /**
     * check whether an authenticated user is subscribed to a thread
     *
     * @return boolean
     */
    public function getIsSubscribedAttribute()
    {
        $attributes = ['user_id' => auth()->id()];
        return $this->subscriptions()->where($attributes)->exists();
    }

    /**
    * Check if a thread has been updated since a given user last read it
    *
    * @param User $user
    */
    public function hasUpdateFor($user)
    {
      if(auth()->check()){
        // Get the cache key for when a user read a thread
        $key = $user->getCacheKeyForVisitedThread($this);

        return $this->updated_at > cache($key);
      }
    }

    public function visits()
    {
        return new Visit($this);
    }

    public function isSolved()
    {
      if($this->best_reply_id != null){
        return true;
      }
      return false;
    }


}
