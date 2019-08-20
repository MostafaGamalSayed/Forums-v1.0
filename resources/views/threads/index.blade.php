@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            @guest
            <p class="text-center text-muted font-weight-bold mt-2">
                Please <a href="{{ route('login') }}">sign in</a> || <a href="{{ route('register') }}">sign up</a> to create threads and participate in the forum
            </p>
            @endguest

            <div class="card">
                <div class="card-header font-weight-bold">
                    {{ request()->route()->parameter('channel')? $channel->name . ' Channel threads' : 'All Threads' }}
                </div>

                <div class="card-body">
                    @forelse($threads as $thread)
                    <article>
                        <h4 class="d-inline">
                            <a href="{{ route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}">
                              @if($thread->hasUpdateFor(auth()->user()))
                                <strong>
                                  {{ $thread->title }}
                                </strong>
                              @else
                                {{ $thread->title }}
                              @endif
                            </a>
                        </h4>
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
                        <p class="lead mt-1">{{ $thread->body }}</p>

                        <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" class="d-inline">
                            <span class="badge badge-secondary p-1 text-monospace">
                                {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count )}}
                            </span>
                        </a>

                        <a href="#" class="d-inline float-right">
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
                    @endforelse
                </div>
            </div>
            <div class="mt-3">
                {{ $threads->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
