<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Reply;
use App\Thread;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Inspections\Spam;
use App\Rules\detectSpam;

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
        request()->validate([
          'body' => ['required', new detectSpam()]
        ]);

          $reply = $thread->addReply([
              'body' => request('body'),
              'user_id' => auth()->id()
          ]);

          // Update the updated_at field of the thread
          $reply->thread->update([
            'updated_at' => Carbon::now()
          ]);

          if(request()->expectsJson()){
              return $reply->load('owner');
          }


          return redirect()
              ->route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id])
              ->with('flash', 'Reply has been created!');
    }


    public function update(Request $request,  Thread $thread, Reply $reply)
    {
          $this->authorize('update', $reply);

          try{
            $this->validateReply();

            $reply->update(request(['body']));

          }catch(\Exception $e){
            return response('Your reply can\'t be updated at time', 422);
          }
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


    public function validateReply() {
      request()->validate([
        'body' => 'required'
      ]);
      // Detect any spam
      app(Spam::class)->detect(request('body'));
    }
}
