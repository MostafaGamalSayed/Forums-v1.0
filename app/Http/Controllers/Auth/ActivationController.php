<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class ActivationController extends Controller
{
    public function index()
    {
      $user = User::where('confirmation_token', request('token'))->first();

      if(!$user){
        return redirect()->route('home');
      }

      $user->activate();

      return redirect()->route('home')->with('success', 'Your account has been activated successfully.');

    }
}
