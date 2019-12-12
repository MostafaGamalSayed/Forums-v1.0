@extends('layouts.app')

@section('styles')

<style>
    blockquote {
      background: #f9f9f9;
      border-left: 10px solid #ccc;
      margin: 1.5em 10px;
      padding: 0.5em 10px;
      quotes: "\201C""\201D""\2018""\2019";
    }
    blockquote:before {
      color: #ccc;
      content: open-quote;
      font-size: 4em;
      line-height: 0.1em;
      margin-right: 0.25em;
      vertical-align: -0.4em;
    }
    blockquote p {
     display: inline;
    }
</style>
@endsection

@section('content')
<thread-view content="{{ $thread->body }}" inline-template>
    <div class="container">
        <section class="section section-blog-info">
            @GuestAlert
            @endGuestAlert
            {{-- <div class="row justify-content-center">
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
        <p>{!! Markdown::convertToHtml($thread->body) !!}</p>
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
        </div> --}}
        <div class="row">
            <div class="col-3">
                <a href="{{ route('thread.create') }}" class="btn btn-round btn-gradient-primary btn-block text-uppercase mb-4">new discussion</a>
                @auth
                <button class="btn  btn-neutral btn-round btn-block text-uppercase mb-5" id="focusReply">Reply</button>
                @endauth

                @SidebarLinks
                @endSidebarLinks
            </div>
            <div class="col-9">
                <div class="card" style="box-shadow: none">
                    <div class="card-header bg-secondary">
                        <div class="d-flex align-items-center">
                            <h5 class="h3 mb-0 text-body font-weight-bold">{{ $thread->title }}</h5>
                            @auth
                            <subscribe :thread="{{ $thread }}"></subscribe>
                            @endauth
                        </div>
                    </div>
                    <div class="card-header d-flex align-items-center">
                        <div class="d-flex align-items-center">
                            <a>
                                <img src="{{ asset('storage/' . $thread->owner->avatar()) }}" class="avatar">
                            </a>
                            <div class="mx-3">
                                <a href="{{ route('user.profile', $thread->owner->name) }}" class="text-dark  font-weight-600 text-sm">{{ $thread->owner->name }}</a>
                                <small class="d-block text-muted"><i class="fa fa-clock mr-1"></i>{{ $thread->created_at->diffForHumans() }}</small>
                            </div>
                        </div>
                        <div class="text-right ml-auto">
                            <a href="{{ route('channel.index', $thread->channel->slug) }}">
                                <span class="badge badge-pill badge-primary d-inline-block p-2" style="min-width: 120px">
                                    {{ $thread->channel->name }}
                                </span>
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <p class="mb-4" v-html="body">

                        </p>
                        <div class="row align-items-center my-3 pb-3 border-bottom">
                            <div class="col-sm-6">
                                <div class="icon-actions">
                                    <i class="fa fa-comment"></i>
                                    <span class="text-muted">
                                        {{ $thread->replies_count }}
                                        {{ str_plural('Reply', $thread->replies_count) }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-sm-6 d-none d-sm-block">
                                <div class="d-flex align-items-center justify-content-sm-end">
                                    <div class="avatar-group">
                                        @foreach ($thread->replies->unique('owner')->take(5) as $reply)
                                        <a class="avatar avatar-xs rounded-circle">
                                            <img alt="Image placeholder" src="{{ asset('storage/' . $reply->owner->avatar()) }}" class="">
                                        </a>
                                        @endforeach
                                    </div>
                                    <small class="pl-2 font-weight-bold">
                                        @if($thread->replies->unique('owner')->count() > 5)
                                            and {{ $thread->replies_count - 5 }}+ more
                                            @endif
                                    </small>
                                </div>
                            </div>
                        </div>
                        <!-- Comments -->
                        <div class="mb-1">
                            <replies></replies>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </section>
        </div>
</thread-view>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#focusReply').click(function() {
            $('#thread-reply').focus();
        });
        $('table').addClass('table table-bordered');
        $('thead').addClass('bg-default text-white');
    });
</script>
@endsection
