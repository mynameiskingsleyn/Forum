<?php

namespace Forum\Filters;

use Illuminate\Http\Request;

abstract class Filters
{
    /**
    * @var Request
    */
    protected $request;
    protected $builder;
    protected $filters =[];
    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function apply($builder)
    {
        // We apply our filters to the builder..
        //dd($this->request->only($this->filters));
        $this->builder = $builder;
        //dd($this->getFilters());
        // collect($this->getFilters())
        //       ->filter(function ($value, $filter) {
        //           //return $this->hasMethod($filter);
        //           return method_exists($this, $filter);
        //       })
        //       ->each(function ($value, $filter) {
        //           $this->$filter($value);
        //       });
        foreach ($this->getFilters() as $filter => $value) {
            if ($this->hasMethod($filter)) {
                $this->$filter($value);
                //dd($value);
            }
        }


        return $this->builder;
    }
    protected function hasMethod($filter):bool
    {
        return method_exists($this, $filter);
    }
    protected function getFilters()
    {
        return $this->request->only($this->filters);// works for me but..
        //return $this->request->intersect($this->filters);
    }
}
