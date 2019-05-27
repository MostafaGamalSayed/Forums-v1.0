@extends('layouts.app')

@section('content')
    <thread-view inline-template>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="d-inline"><a href="{{ route('user.profile', $thread->owner->name) }}">{{ $thread->owner->name }}</a> published {{ $thread->created_at->diffForHumans() }}</h6>
                            @can('delete', $thread)
                            <form action="{{ route('thread.destroy', $thread->id) }}" method="post" class="d-inline">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button type="submit" class="float-right bg-transparent border-0" style="cursor: pointer">
                                    <i class="fas fa-trash-alt text-danger"></i>
                                </button>
                            </form>
                            @endcan
                        </div>

                        <div class="card-body">
                            <h3 class="text-muted"><b>{{ $thread->title }}</b></h3>
                            <p>{{ $thread->body }}</p>
                        </div>
                    </div>
                    <br>
                    <br>
                    @if(count($thread->replies) > 0)
                        <div class="page-header">
                            <h3 class="text-muted" id="replies">Replies</h3>
                        </div>
                        <hr>
                    @endif
                    <replies></replies>
                    @guest
                    <p class="text-center text-muted font-weight-bold mt-3">
                        Please <a href="{{ route('login') }}">sign in</a> || <a href="{{ route('register') }}">sign up</a> to reply and participate in the forum
                    </p>
                    @endguest
                </div>
                <div class="col-md-4">
                    <subscribe :thread="{{ $thread }}"></subscribe>
                </div>
            </div>
        </div>
    </thread-view>
@endsection
