<?php

namespace App\Http\Controllers;

use App\Channel;
use App\Filters\ThreadFilters;
use App\Thread;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Inspections\Spam;
use Illuminate\Support\Facades\Redis;


class ThreadsController extends Controller
{

    public function __construct(){
        $this->middleware('auth')->except(['index', 'show']);
    }


    /**
     * @param Channel $channel
     * @param ThreadFilters $filters
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Channel $channel, ThreadFilters $filters)
    {
        $threads = $this->getThreads($channel, $filters);

        $trendingThreads = collect(Redis::zrevrange('trending_threads', 0, 4))->map(function($trend){
          return json_decode($trend);
        });
        //dd($trendingThreads);

        return view('threads.index', compact('threads', 'channel', 'trendingThreads'));
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
            'channel' => ['required', Rule::exists('channels', 'id')]
        ]);

        try{
          $spam->detect(request('body'));

          // Create the new thread
          $thread = auth()->user()->addThread([
              'title' => request('title'),
              'body' => request('body'),
              'channel_id' => request('channel')
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
          'thread_id' => $thread->id,
          'channel_slug' => $thread->channel->slug,
          'title' => $thread->title,
          'body' => $thread->body,
          'owner' => $thread->owner
        ]));

        return view('threads.show', compact('thread'));
    }


    public function update(Request $request, Thread $thread)
    {
        //
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

}
