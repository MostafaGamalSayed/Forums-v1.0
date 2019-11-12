<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Redis;

class Visit extends Model
{
    public $thread;

    public function __construct($thread)
    {
      $this->thread = $thread;
    }

    public function reset()
    {
      Redis::del($this->cachKey());
    }

    public function cachKey()
    {
      return 'threads.' . $this->thread->id . '.visits';
    }

    public function count()
    {
      return Redis::get($this->cachKey()) ?? 0;
    }

    public function record()
    {
      Redis::incr($this->cachKey());
      return $this->thread;
    }


}
