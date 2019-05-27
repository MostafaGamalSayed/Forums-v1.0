<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ThreadSubscription extends pivot
{
    protected $table = 'thread_subscriptions';
}
