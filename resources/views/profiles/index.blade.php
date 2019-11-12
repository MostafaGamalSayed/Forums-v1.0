@extends('layouts.app')

@section('content')
<div class="container">
    {{-- <div class="row">
        <div class="col-md-8">
            <div class="jumbotron">
                <h3 class="text-dark font-weight-bold">{{ $user->name }}</h3>
    <p class="text-info"><b>member since {{ $user->created_at->diffForHumans() }}</b></p>
    <hr>
    <span class="mr-1 text-secondary"><b>Threads:</b> {{ $user->threads->count() }} -</span>
    <span class="mr-1 text-secondary"><b>Replies:</b> {{ $user->replies->count() }} -</span>
    <span class="mr-1 text-secondary"><b>Favorites:</b> {{ $user->getUserFavoritesCount() }}</span>
</div>
</div>
@can ('update', $user)
<div class="col-md-4">
    <div class="jumbotron ">
        <div class="media">
            <img src="{{ asset('storage/' . $user->avatar()) }}" class="mr-3" style="height: 64px; width: 64px" alt="...">
            <div class="media-body">
                <h5 class="mt-0">Update Your Avatar</h5>
                <form action="{{ route('avatar.upload', $user->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="avatar" />
                    <button type="submit" class="btn btn-primary mt-3">Upload</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endcan

<div class="col-md-10">
    <h3 class="page-header text-muted font-weight-bold">
        {{ $user->name }}'s Activities
    </h3>
    <hr>
    @forelse($activities as $date => $dateActivities)
    <h5 class="title text-muted font-weight-bold mt-5">
        <span class="badge badge-primary">
            {{ $date }}
        </span>
    </h5>

    <div class="border-left border-info">
        @foreach($dateActivities as $activity)
        @include('activities.' . $activity->type)
        @endforeach
    </div>
    @empty
    <p class="text-muted text-center font-weight-bold">{{ $user->name }} has not activities yet !</p>
    @endforelse
</div>
</div> --}}
<div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card card-profile pb-4 mt-5">
            <div class="card-header bg-info" style="background-image: url('{{ asset('argon/assets/img/ill/inn.svg') }}')">
                <div class="card-avatar">
                    <a href="javascript:;">
                        <img class="img img-raised rounded-circle" src="{{ asset('storage/' . $user->avatar()) }}">
                    </a>
                </div>
            </div>
            <div class="card-body pt-0">
                <div class="d-flex justify-content-between">
                    <a href="javascript:;" class="btn btn-sm btn-info mr-4 mt-3">Connect</a>
                    <a href="javascript:;" class="btn btn-sm btn-default float-right mt-3">Message</a>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card-profile-stats d-flex justify-content-center">
                            <div>
                                <span class="heading">{{ $user->threads->count() }}</span>
                                <span class="description">Thread</span>
                            </div>
                            <div>
                                <span class="heading">{{ $user->replies->count() }}</span>
                                <span class="description">Replies</span>
                            </div>
                            <div>
                                <span class="heading">{{ $user->getUserFavoritesCount() }}</span>
                                <span class="description">Favorites</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <h5 class="h4">
                        {{ $user->name }}
                    </h5>
                    <div class="font-weight-300">
                        <i class="ni location_pin mr-2"></i>
                        Member since {{ $user->created_at->diffForHumans() }}
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mx-auto">
        <div class="card">
            <div class="card-header">
                <h5 class="h3 mb-0">{{ $user->name }} Activity feed</h5>
            </div>
            <div class="card-body">
              @forelse($activities as $date => $dateActivities)
              <h5 class="title text-muted font-weight-bold mt-5">
                  <span class="badge badge-primary">
                      {{ $date }}
                  </span>
              </h5>

              <div class="border-left border-info">
                  @foreach($dateActivities as $activity)
                    @include('activities.' . $activity->type)
                  @endforeach
              </div>
              @empty
              <p class="text-muted text-center font-weight-bold">{{ $user->name }} has not activities yet !</p>
              @endforelse
            </div>
        </div>
    </div>
</div>
</div>
</div>
@endsection
