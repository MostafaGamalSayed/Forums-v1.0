@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
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
                            <input type="file"  name="avatar" />
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
    </div>
</div>
@endsection
