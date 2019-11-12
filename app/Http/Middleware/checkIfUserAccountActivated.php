<?php

namespace App\Http\Middleware;

use Closure;

class checkIfUserAccountActivated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!auth()->user()->confirmed){
          return redirect()->route('thread.index');
        }
        return $next($request);
    }
}
