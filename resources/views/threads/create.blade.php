@extends('layouts.app')

@section('content')
    <section class="section section-blog-info">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card mb-5">
                  <div class="card-header bg-danger">
                      <h3 class="text-white">Create Thread</h3>
                  </div>

                  <div class="card-body">
                      <form action="{{ route('thread.store') }}" method="post">
                          @csrf
                          <div class="form-group {{ $errors->has('title') ? 'has-error': '' }}">
                              <label for="inputTitle">Title</label>
                              <input name="title" id="inputTitle" class="form-control form-control-alternative" value="{{ old('title') }}" placeholder="Enter the title..." autocomplete="off" />
                              @if($errors->has('title'))
                                  <span class="has-error has-feedback text-danger">{{ $errors->first('title') }}</span>
                              @endif
                          </div>

                          <div class="form-group {{ $errors->has('body') ? 'has-error' : '' }}">
                              <label for="inputBody">Body</label>
                              {{-- <textarea name="body" class="form-control form-control-alternative" id="inputBody" rows="3" placeholder="Enter the body..." >{{ old('body') }}</textarea> --}}
                              <text-editor name="body"></text-editor>
                              @if($errors->has('body'))
                                  <span class="has-error has-feedback text-danger">{{ $errors->first('body') }}</span>
                              @endif
                          </div>

                          <div class="form-group {{ $errors->has('channel') ? 'has-error' : '' }}">
                              <label for="inputChannel">Channel</label>
                              <select name="channel_id" id="inputChannel" class="form-control" >
                                  <option value="">Select...</option>
                                  @foreach($channels as $channel)
                                      <option value="{{ $channel->id }}" {{ old('channel') == $channel->id ? 'selected' : '' }}> {{ $channel->name }}</option>
                                  @endforeach
                              </select>
                              @if($errors->has('channel'))
                                  <span class="has-error has-feedback text-danger">{{ $errors->first('channel') }}</span>
                              @endif
                          </div>


                          <button type="submit" class="btn btn-outline-danger float-right">Create Thread</button>
                      </form>
                  </div>
              </div>
          </div>
      </div>
    </section>
@endsection
