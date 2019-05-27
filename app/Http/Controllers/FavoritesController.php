<?php

namespace App\Http\Controllers;

use App\Reply;
use Illuminate\Http\Request;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Favorite a reply
     *
     * @param Reply $reply
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Reply $reply)
    {
       if(request()->expectsJson()){
           $reply->favorite();
       }
        //return back();
    }

    public function destroy(Reply $reply)
    {
        if(request()->expectsJson()){
            $reply->unFavorite();
        }
    }
}
