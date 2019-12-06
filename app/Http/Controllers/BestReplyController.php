<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Reply;

class BestReplyController extends Controller
{
    public function store(Reply $reply)
    {
        if(request()->expectsJson()){
          $updated = $reply->markAsBestReply();
          if($updated){
            return response([
              'message' => 'updated'
            ]);
          }
        }
    }
}
