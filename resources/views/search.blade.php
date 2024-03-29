@extends('layouts.app') 

@section('styles')
  <style>
    ul .sidebarLink:hover {
        background-color: #f6f6f6;
    }

    .thread-title:hover {
        text-decoration: underline;
    }
  </style>
@endsection

@section('content')
  <section class="section section-blog-info">
      <div class="container">
        <thread-search query="{{ request('query') }}" ></thread-search>
      </div>
  </section>
@endsection                                                                                                                                                                                                                                                                                                                                                              
