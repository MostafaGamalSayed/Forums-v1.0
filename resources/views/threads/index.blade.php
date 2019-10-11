@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            @guest
            <p class="text-center text-muted font-weight-bold mt-2">
                Please <a href="{{ route('login') }}">sign in</a> || <a href="{{ route('register') }}">sign up</a> to create threads and participate in the forum
            </p>
            @endguest
        </div>
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">
                    <h6 class="font-weight-bold">
                        {{ request()->route()->parameter('channel')? $channel->name . ' Channel threads' : 'All Threads' }}
                    </h6>
                </div>

                <div class="card-body">
                    <ul class="list-unstyled">
                        @forelse ($threads as $thread)
                        <li class="media my-4 p-3">
                            <img src="{{ asset('storage/' . $thread->owner->avatar()) }}" style="height: 60px; width: 60px" class="mr-3 rounded-circle" alt="...">
                            <div class="media-body">
                                <span>
                                    @can('delete', $thread)
                                    <form action="{{ route('thread.destroy', $thread->id) }}" method="post">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="float-right bg-transparent border-0" style="cursor: pointer">
                                            <i class="fas fa-trash-alt text-danger"></i>
                                        </button>
                                    </form>
                                    @endcan
                                </span>
                                <h6 class="mt-0 mb-0">
                                    <a href="{{ route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}">
                                        @if($thread->hasUpdateFor(auth()->user()))
                                            <span class="font-weight-bold">{{ $thread->title }}</span>
                                            @else
                                            {{ $thread->title }}
                                            @endif
                                    </a>
                                </h6>
                                <small class="d-block text-muted">
                                    Posted by:
                                    <a href="{{ route('user.profile', $thread->owner->name) }}">
                                        {{ $thread->owner->name }}
                                    </a>
                                    {{ $thread->created_at->diffForHumans() }}
                                </small>
                                <p class="mt-1">
                                    {{ $thread->body }}
                                </p>
                                <div class="row">
                                  <div class="col-xs-2">
                                    <span class="mt-1 ml-3">
                                        <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" class="d-inline  text-muted">
                                            <i class="fa fa-comment"></i>
                                            {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count )}}
                                        <a>
                                    </span>
                                  </div>
                                  <div class="offset-8 col-xs-2">
                                    <span class="mt-1">
                                      <a href="{{ route('channel.index', $thread->channel->slug) }}" class="d-inline">
                                          <span class="badge badge-primary p-1 text-monospace">
                                              {{ $thread->channel->name }}
                                          </span>
                                      </a>
                                    </span>
                                  </div>
                                </div>
                            </div>
                        </li>
                        @empty
                        <p class="text-center text-muted">
                            <small class="font-weight-bold">No threads yet !</small>
                        </p>
                        @endforelse
                    </ul>
                    {{-- @forelse($threads as $thread)
                    <article>
                        <h5 class="d-inline">
                            <a href="{{ route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}">
                    @if($thread->hasUpdateFor(auth()->user()))
                        <strong>
                            {{ $thread->title }}
                        </strong>
                        @else
                        {{ $thread->title }}
                        @endif
                        </a>
                        </h5>
                        @can('delete', $thread)
                        <form action="{{ route('thread.destroy', $thread->id) }}" method="post">
                            @csrf
                            {{ method_field('DELETE') }}
                            <button type="submit" class="float-right bg-transparent border-0" style="cursor: pointer">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </button>
                        </form>
                        @endcan
                        <small class="d-block text-muted">Posted by: <a href="{{ route('user.profile', $thread->owner->name) }}">{{ $thread->owner->name }}</a> {{ $thread->created_at->diffForHumans() }}</small>
                        <p class="mt-1">{{ $thread->body }}</p>

                        <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" class="d-inline">
                            <span class="badge badge-secondary p-1 text-monospace">
                                {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count )}}
                            </span>
                        </a>

                        <a href="{{ route('channel.index', $thread->channel->slug) }}" class="d-inline float-right">
                            <span class="badge badge-primary p-1 text-monospace">
                                {{ $thread->channel->name }}
                            </span>
                        </a>
                        </article>
                        @if(! $loop->last)
                        <hr>
                        @endif
                        @empty
                        <p>There is no threads!</p>
                        @endforelse --}}
                </div>
            </div>
            <div class="mt-3">
                {{ $threads->links() }}
            </div>
        </div>
        <div class="col-md-4">
            <div class="card card-default">
                <div class="card-header">
                    <h6 class="font-weight-bold">Trending Threads</h6>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        @forelse ($trendingThreads as $key => $trend)
                        <li class="media my-4">
                            <img src="{{ $trend->owner->avatar_path ?  asset('storage/' . $trend->owner->avatar_path) : asset('storage/avatars/default.png') }}" style="height: 50px; width: 50px" class="mr-3" alt="...">
                            <div class="media-body">
                                <small class="mt-0 mb-1 font-weight-bold">
                                    <a href="{{ route('thread.show', ['channel' => $trend->channel_slug, 'thread' => $trend->thread_id]) }}">
                                        {{ $trend->title }}
                                    </a>
                                </small>
                                <small class="d-block text-muted">
                                    Posted by: <a href="{{ route('user.profile', $trend->owner->name) }}">{{ $trend->owner->name }}</a>
                                </small>

                            </div>
                        </li>
                        @empty
                        <p class="text-center text-muted">
                            <small class="font-weight-bold">No trending threads yet !</small>
                        </p>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
