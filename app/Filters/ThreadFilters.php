<?php

namespace App\Filters;


use App\User;

class ThreadFilters extends Filters
{
    protected $query, $request;

    protected $filters = ['by', 'popular', 'unanswered', 'subscribed'];

    /**
     * Filter the threads by the user
     *
     * @param $userName
     * @return mixed
     */
    protected function by($userName)
    {
        $user = User::where('name', $userName)->firstOrFail();
        return $this->query->where('user_id', $user->id);
    }


    protected function popular()
    {
        return $this->query->orderBy('replies_count', 'desc');
    }

    protected function unanswered(){
        return $this->query->doesntHave('replies')->get();
    }

    protected function subscribed()
    {
        return $this->query->whereHas('subscriptions', function($query){
            $query->where('user_id', auth()->id());
        })->get();
    }
}