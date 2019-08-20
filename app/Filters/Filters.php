<?php

namespace App\Filters;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

abstract class Filters
{
    protected $query, $request;

    protected $filters = [];


    /**
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    /**
     * @param Builder $query
     * @return mixed
     */
    public function apply($query)
    {
        $this->query = $query;

        foreach($this->getFilters() as  $filter => $value){
           if(method_exists($this, $filter)){
                $this->$filter($value);
            }
        }
        return $query;
    }

    /**
     * @return mixed
     */
    protected function getFilters()
    {
        // $this->getFilters will return something like: array:1 ["by" => "110"]
        return $this->request->only($this->filters);
    }
}
