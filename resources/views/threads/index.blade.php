@extends('layouts.app')

@section('styles')
<style>
    ul .media:hover {
        background-color: #f4f5f7;
    }

    ul .sidebarLink:hover {
        background-color: #f6f6f6;
    }

    .thread-title:hover {
        text-decoration: underline;
    }

    .selectWrapper {
        border-radius: 36px;
        display: inline-block;
        overflow: hidden;
        background: #cccccc;
        border: 1px solid #cccccc;

    }

    .selectBox {
        width: 140px;
        height: 40px;
        border: 0px;
        outline: none;
    }
</style>
@endsection

@section('content')
<section class="section section-blog-info">
    <div class="container">
        @GuestAlert
        @endGuestAlert
        <div class="row">
            <div class="col-lg col-sm-12">
                <a href="{{ route('thread.create') }}" class="btn btn-round btn-primary btn-block text-uppercase">new discussion</a>
            </div>
            <div class="col-6 d-none d-lg-block d-xl-block">
                <form id="ThreadFilterForm">
                    @csrf
                    <div class="selectWrapper">
                        <select class="form-control selectBox" id="channelFilter">
                            <option selected disabled hidden>
                                Select Channel
                            </option>
                            @foreach($channels as $channel)
                              @if(request('channel'))
                                <option value="{{ $channel->slug }}" {{ request('channel')->slug == $channel->slug ? 'selected' : '' }}>
                                    {{ $channel->name }}
                                </option>
                              @else
                                <option value="{{ $channel->slug }}">
                                    {{ $channel->name }}
                                </option>
                              @endif
                            @endforeach
                        </select>
                    </div>
                    <button type="submit" id="channelFilterButton" class="btn btn-gradient-warning btn-round float-right mb-4">Filter</button>
                </form>
            </div>
            <div class="col d-none d-lg-block d-xl-block">
                <form action="{{ route('thread.search') }}" method="get">                
                    <div class="input-group input-group-alternative mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input name="query" class="form-control" id="searchInput"  placeholder="Search" type="text">
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-3">
                @SidebarLinks
                @endSidebarLinks
            </div>
            <div class="col-9">
                <ul class="list-unstyled">
                    @forelse ($threads as $thread)
                    <li class="media my-4 p-3 rounded">
                        <img src="{{ asset('storage/' . $thread->owner->avatar()) }}" style="height: 60px; width: 60px" class="mr-3 rounded-circle" alt="...">
                        <div class="media-body">
                            <div class="row ">
                                <div class="col-7">
                                    <h6 class="mt-0 mb-0">
                                        <a href="{{ route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]) }}" class="thread-title text-dark font-weight-bolder">
                                            @if($thread->hasUpdateFor(auth()->user()))
                                                <span class="font-weight-bold">{{ $thread->title }}</span>
                                                @else
                                                {{ $thread->title }}
                                                @endif
                                        </a>
                                    </h6>
                                    <small class="d-block text-muted">
                                        <a href="{{ route('user.profile', $thread->owner->name) }}" class="text-uppercase font-weight-bold">
                                            {{ $thread->owner->name }}
                                        </a>
                                        Posted
                                        <span class="font-weight-bold">
                                            {{ $thread->created_at->diffForHumans() }}
                                        </span>
                                    </small>
                                </div>
                                <div class="col-5 text-right">
                                    <span class="mr-2 text-muted">
                                        <i class="fa fa-eye"></i>
                                        {{ $thread->visits()->count() }}
                                    </span>
                                    <span class="mr-2">
                                        <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]) }}" class="d-inline  text-muted">
                                            <i class="fa fa-comment"></i>
                                            {{ $thread->replies_count }}
                                        </a>
                                    </span>
                                    <span>
                                        <a href="{{ route('channel.index', ['channel' => $thread->channel->slug]) }}" class="badge badge-pill d-inline-block border border-danger text-danger w-50" style="background-color: transparent;">
                                            {{ $thread->channel->name }}
                                        </a>
                                    </span>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="col-12">
                                    <span>
                                        <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]) }}" class="d-inline text-muted">
                            <small>
                                <i class="fa fa-comment"></i>
                                {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count )}}
                            </small>
                            <a>
                                </span> |
                                <span class="text-muted">
                                    <small>
                                        <i class="fa fa-eye mr-1"></i>
                                        {{ $thread->visits() }} {{ str_plural('Visit', $thread->visits()) }}
                                    </small>
                                </span>
                                @can('delete', $thread) |
                                @endcan
                                <span>
                                    @can('delete', $thread)
                                    <form action="{{ route('thread.destroy', $thread->id) }}" method="post" class="d-inline">
                                        @csrf
                                        {{ method_field('DELETE') }}
                                        <button type="submit" class="bg-transparent border-0 text-danger" style="cursor: pointer">
                                            <small>
                                                <i class="fa fa-trash"></i>
                                                Delete
                                            </small>
                                        </button>
                                    </form>
                                    @endcan
                                </span>
                        </div>

            </div> --}}
        </div>
        </li>
        @empty
        <p class="text-center text-muted">
            <small class="font-weight-bold">No threads yet !</small>
        </p>
        @endforelse
        </ul>
        <ul class="pagination pagination-primary text-center">
            {{ $threads->appends(request()->input())->links() }}
        </ul>
    </div>
    </div>

    {{-- <div class="row justify-content-center">
          <div class="col-md-12">
              @guest
              <p class="text-center text-muted font-weight-bold mt-2">
                  Please <a href="{{ route('login') }}">sign in</a> || <a href="{{ route('register') }}">sign up</a> to create threads and participate in the forum
    </p>
    @endguest
    </div>
    </div> --}}
    {{-- <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card card-plain shadow p-3">
                <h6 class="text-center heading-title text-warning mb-0">
                    {{ request()->route()->parameter('channel')? $channel->name . ' Channel threads' : 'Threads Feed' }}
    </h6>
    <hr />
    <ul class="list-unstyled">
        @forelse ($threads as $thread)
        <li class="media my-4 p-3 rounded" style="background: #f4f5f7">
            <img src="{{ asset('storage/' . $thread->owner->avatar()) }}" style="height: 60px; width: 60px" class="mr-3 rounded-circle" alt="...">
            <div class="media-body">
                <h6 class="mt-0 mb-0">
                    <a href="{{ route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]) }}">
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
                <br />
                <div class="row">
                    <div class="col-12">
                        <span>
                            <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->slug]) }}" class="d-inline  text-muted">
                                <small>
                                    <i class="fa fa-comment"></i>
                                    {{ $thread->replies_count }} {{ str_plural('Reply', $thread->replies_count )}}
                                </small>
                                <a>
                        </span> |
                        <span class="text-muted">
                            <small>
                                <i class="fa fa-eye mr-1"></i>
                                {{ $thread->visits() }} {{ str_plural('Visit', $thread->visits()) }}
                            </small>
                        </span>
                        @can('delete', $thread) |
                        @endcan
                        <span>
                            @can('delete', $thread)
                            <form action="{{ route('thread.destroy', $thread->id) }}" method="post" class="d-inline">
                                @csrf
                                {{ method_field('DELETE') }}
                                <button type="submit" class="bg-transparent border-0 text-danger" style="cursor: pointer">
                                    <small>
                                        <i class="fa fa-trash"></i>
                                        Delete
                                    </small>
                                </button>
                            </form>
                            @endcan
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
    </div>
    </div> --}}
    {{-- <div class="col-md-4">
            <div class="card bg-secondary shadow text-center p-3">
              <h6 class="text-center heading-title text-warning">Trending Threads</h6>
              <hr />
              <ul class="list-group">
                  @forelse ($trendingThreads as $key => $trend)
                  <li class="media my-4">
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
    </div> --}}
    {{-- </div> --}}


    {{-- <ul class="pagination pagination-warning">
        {{ $threads->links() }}
    </ul> --}}
    </div>
</section>
@endsection

@section('scripts')
<script>
    $('#ThreadFilterForm').submit(function(event) {
        event.preventDefault();
        var slug = $('#channelFilter').val();
        window.location.href = '/' + slug + '/threads';
    });


    $('#channelFilter').on('change', function () {
      $('#channelFilterButton').prop('disabled', !$(this).val());
    }).trigger('change');
</script>
@endsection
