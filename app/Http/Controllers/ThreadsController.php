<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Inspections\Spam;
use App\Rules\detectSpam;
use Illuminate\Support\Facades\Redis;


class ThreadsController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except(['index', 'show', 'trending']);
        //$this->middleware('activated')->only(['create', 'store']);
    }


    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        $channels = Channel::all();

        return view('threads.index', compact('threads', 'channel', 'channels','trendingThreads'));
    }


    public function create()
    {
        return view('threads.create');
    }


    public function store(Request $request, Spam $spam)
    {
        // Validate the requested data
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'channel_id' => ['required', Rule::exists('channels', 'id')]
        ]);

        try{
          // Create the new thread
          $thread = auth()->user()->addThread([
              'title' => request('title'),
              'body' => request('body'),
              'channel_id' => request('channel_id')
          ]);

          // Redirect to show the new thread
          return redirect()
              ->route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id])
              ->with('flash', 'Thread has been created!');
        }catch(\Exception $e){

          return back()
              ->with('flash', 'Your Thread can\'t be published at time.');
        }
    }


    public function show(Channel $channel, Thread $thread)
    {
        if(auth()->check()){
          auth()->user()->read($thread);
        }

        // increment the given thread visiting scores by one
        Redis::zincrby('trending_threads', 1, json_encode([
          'id' => $thread->id,
          'title' => $thread->title,
          'body' => $thread->body,
          'owner' => $thread->owner,
          'channel' => $thread->channel,
          'avatar' => $thread->owner->avatar()
        ]));

        // Update the thread visits counter
        $thread->visits()->record();

        return view('threads.show', compact('thread'));
    }


    /**
     * @param Thread $thread
     */
    public function destroy(Thread $thread)
    {
        $this->authorize('delete', $thread);
        $thread->delete();
        return redirect()->route('thread.index');
    }

    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return $this|\Illuminate\Database\Eloquent\Collection
     */
    protected function getThreads(Channel $channel, ThreadFilters $filters)
    {
        $threads = $channel->exists ? $channel->threads()->latest() : Thread::filter($filters)->latest();

        $threads = $threads->with(['owner', 'channel'])->paginate(10);

        return $threads;
    }


    public function trending()
    {
      //$trendingThreads = Redis::command('ZREVRANGE',['trending_threads', 0, 4, 'WITHSCORES']);
      $threads = collect(Redis::zrevrange('trending_threads', 0, 4))->map(function($trend){
        return json_decode($trend);
      });
      $channels = Channel::all();

      return view('threads.trending', compact('threads', 'channels'));
    }


}
