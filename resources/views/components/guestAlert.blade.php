@guest
<div class="alert alert-secondary alert-dismissible fade show font-weight-bold text-body text-center mb-5"  role="alert">
    <span class="alert-inner--text">
      <i class="fa fa-info-circle mr-1"></i>
      Please  <a href="{{ route('login') }}" class="btn btn-sm btn-neutral"> Signin</a>- OR - <a href="{{ route('register') }}" class="btn btn-neutral btn-sm">Signup</a>
       to participate in the forum
    </span>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endguest
