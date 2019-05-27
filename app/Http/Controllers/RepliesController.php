<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;

class RepliesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }


    public function index($channel, Thread $thread)
    {
        return $thread->replies()->paginate(10);
    }



    public function store(Request $request,$channel,Thread $thread)
    {
        $this->validate($request, [
            'body' => 'required'
        ]);
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if(request()->expectsJson()){
            return $reply->load('owner');
        }

        return redirect()
            ->route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id])
            ->with('flash', 'Reply has been created!');
    }


    public function update(Thread $thread, Reply $reply)
    {
        $this->authorize('update', $reply);
        $reply->update(request(['body']));
    }


    public function destroy($thread, Reply $reply)
    {
        $this->authorize('delete', $reply);
        if($reply->delete()){
            if(request()->expectsJson()){
                return response(['status' => 'the reply deleted successfully']);
            }
            return back();
        }
    }
}
