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
                <form id="searchForm">
                    @csrf
                    <div class="input-group input-group-alternative mb-4">
                        <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fa fa-search"></i></span>
                        </div>
                        <input class="form-control" id="searchInput" value="{{ request('query') ? request('query') : '' }}" placeholder="Search" type="text">
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
                        <img src="{{ asset('storage/' . $thread->avatar) }}" style="height: 60px; width: 60px" class="mr-3 rounded-circle" alt="...">
                        <div class="media-body">
                            <div class="row ">
                                <div class="col-7">
                                    <h6 class="mt-0 mb-0">
                                        <a href="{{ route('thread.show', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" class="thread-title text-dark font-weight-bolder">
                                            {{ $thread->title }}
                                        </a>
                                    </h6>
                                    <small class="d-block text-muted">
                                        <a href="{{ route('user.profile', $thread->owner->name) }}" class="text-uppercase font-weight-bold">
                                            {{ $thread->owner->name }}
                                        </a>                                    
                                    </small>
                                </div>
                                <div class="col-5 text-right">
                                    <span class="mr-2 text-muted">
                                        <i class="fa fa-eye"></i>
                                        {{ \App\Thread::find($thread->id)->visits()->count() }}
                                    </span>
                                    <span class="mr-2">
                                        <a href="{{ route('thread.showReplies', ['channel' => $thread->channel->slug, 'thread' => $thread->id]) }}" class="d-inline  text-muted">
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

                        </div>
                    </li>
                    @empty
                    <div class="alert alert-info text-body font-weight-bold">
                        No Trending threads yet !
                    </div>
                    @endforelse
                </ul>
            </div>
        </div>
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

    $('#searchForm').submit(function(event) {
        event.preventDefault();
        var q = $('#searchInput').val();
        window.location.href = '/threads?query=' + q;
    });

    $('#channelFilter').on('change', function() {
        $('#channelFilterButton').prop('disabled', !$(this).val());
    }).trigger('change');
</script>
@endsection
