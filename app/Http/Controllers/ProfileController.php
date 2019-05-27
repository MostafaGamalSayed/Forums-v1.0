<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function index(User $user)
    {
        $activities =  $user->getUserActivityFeed();
        return view('profiles.index', compact('user', 'activities'));
    }
}
