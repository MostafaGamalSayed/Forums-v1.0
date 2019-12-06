<ul class="nav flex-column text-muted">
  <li class="nav-item sidebarLink p-2 pl-2">
    <a class="nav-link d-inline {{ request('all') ? 'active font-weight-bold' : '' }}" href="{{ route('thread.index', ['all' => 1]) }}">
      <i class="fa fa-paperclip mr-3" aria-hidden="true"></i>
      All Threads
    </a>
  </li>

  @auth
    <li class="nav-item sidebarLink p-2 pl-2">
      <a class="nav-link d-inline {{ request('by') ? 'active font-weight-bold' : '' }}"  href="{{ route('thread.index', ['by' => auth()->user()->name]) }}">
        <i class="fa fa-question-circle mr-3"></i>
        My Questions
      </a>
    </li>


    <li class="nav-item sidebarLink p-2 pl-2">
      <a class="nav-link d-inline {{ request('subscribed') ? 'active font-weight-bold' : '' }}"  href="{{ route('thread.index', ['subscribed' => 1]) }}">
        <i class="fa fa-save mr-3"></i>
        Subscribed
      </a>
    </li>
  @endauth

  <li class="nav-item sidebarLink p-2 pl-2">
    <a class="nav-link d-inline {{ request('popular') ? 'active font-weight-bold' : '' }}"  href="{{ route('thread.index', ['popular' => 1]) }}">
      <i class="fa fa-star mr-3"></i>
      Popular
    </a>
  </li>

  <li class="nav-item sidebarLink p-2 pl-2">
    <a class="nav-link d-inline {{ request()->route()->getName() == 'thread.trending' ? 'active font-weight-bold' : '' }}"  href="{{ route('thread.trending') }}">
      <i class="fa fa-chart-line mr-3"></i>
      Trendig
    </a>
  </li>

  <li class="nav-item sidebarLink p-2 pl-2">
    <a class="nav-link d-inline {{ request('no_answers') ? 'active font-weight-bold' : '' }}" href="{{ route('thread.index', ['no_answers' => 1]) }}">
      <i class="fa fa-reply mr-3"></i>
      No Answers Yet
    </a>
  </li>

  <li class="nav-item sidebarLink p-2 pl-2">
    <a class="nav-link d-inline {{ request('solved') ? 'active font-weight-bold' : '' }}"  href="{{ route('thread.index', ['solved' => 1]) }}">
      <i class="fa fa-check-circle mr-3"></i>
      Solved
    </a>
  </li>

  <li class="nav-item sidebarLink p-2 pl-2">
    <a class="nav-link d-inline {{ request('unSolved') ? 'active font-weight-bold' : '' }}"  href="{{ route('thread.index', ['unSolved' => 1]) }}">
      <i class="fa fa-times mr-3"></i>
      UnSolved
    </a>
  </li>
</ul>
