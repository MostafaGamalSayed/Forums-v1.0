<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Mockery\CountValidator\Exception;

class Reply extends Model
{
    use Favoritable, RecordActivity;

    protected $fillable = [
        'body', 'user_id', 'thread_id'
    ];

    protected $with = ['owner'];

    protected $appends = ['favoritesCount', 'isFavorited'];

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

}
