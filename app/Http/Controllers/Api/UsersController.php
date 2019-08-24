<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UsersController extends Controller
{
    public function index()
    {
      $query = request('name');

      return User::where('name', 'Like', "%$query%")
                ->take(5)
                ->pluck('name');
    }
}
