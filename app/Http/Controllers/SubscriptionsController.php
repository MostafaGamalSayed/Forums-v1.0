<?php

namespace App\Http\Controllers;

use App\Thread;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($channel, Thread $thread)
    {
        if(request()->expectsJson())
        {
            $thread->subscribe();
        }
    }


    public function destroy($channel, Thread $thread)
    {
        if(request()->expectsJson())
        {
            $thread->unSubscribe();
        }
    }
}
