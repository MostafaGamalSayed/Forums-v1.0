<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;
use Carbon\Carbon;

class Reply extends Model
{
    use Favoritable, RecordActivity;

    protected $fillable = [
        'body', 'user_id', 'thread_id'
    ];

    protected $with = ['owner'];

    protected $appends = ['favoritesCount', 'isFavorited', 'ago', 'threadSlug', 'isBestReply'];

    /**
     * A reply belongs to one tread
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function thread(){
        return $this->belongsTo('App\Thread');
    }


    /**
     * A reply belongs to one user
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner(){
        return $this->belongsTo('App\User', 'user_id');
    }


    /**
    * Check if the reply was just published
    */
    public function wasJustPublished()
    {
      return $this->created_at->gt(Carbon::now()->subMinute());
    }


    /**
    * Get the mentioned users in a reply
    *
    * @return array
    */
    public function getMentionedUsers()
    {
      preg_match_all('/\@([\w\-]+)/', $this->body, $matches);
      return $matches[1];
    }

   public function getAgoAttribute()
   {
     return $this->created_at->diffForHumans();
   }

   public function getThreadSlugAttribute()
   {
     return $this->thread->slug;
   }

   public function markAsBestReply()
   {
     return $this->thread->update(['best_reply_id' => $this->id]);
   }

   public function isBestReply()
   {
     return $this->thread->best_reply_id == $this->id;
   }

   public function getIsBestReplyAttribute()
   {
     return $this->isBestReply();
   }

}
