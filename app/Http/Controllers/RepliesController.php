<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Inspections\Spam;
use App\Rules\detectSpam;
use Illuminate\Support\Facades\Gate;
use App\User;
use App\Notifications\YouWereMentioned;


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



    public function store(Channel $channel, Thread $thread)
    {
        // Check if the user has the authorization to create reply
        if(Gate::denies('create', new Reply)){
            return response(
              'You are posting too frequently.Please take a break.',
              429
            );
        }

        // validate the reply
        request()->validate([
          'body' => ['required', new detectSpam()]
        ]);

        // Add new reply
        $reply = $thread->addReply([
            'body' => request('body'),
            'user_id' => auth()->id()
        ]);

        if(request()->expectsJson()){
            return $reply->load('owner');
        }
    }


    public function update(Request $request,  Thread $thread, Reply $reply)
    {
          $this->authorize('update', $reply);

          request()->validate([
            'body' => ['required', new detectSpam()]
          ]);

          $reply->update(request(['body']));

          return $reply;

    }


    public function destroy(Thread $thread, Reply $reply)
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
