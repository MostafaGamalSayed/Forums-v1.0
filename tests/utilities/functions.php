<?php

/**
 * Create a model factory
 *
 * @param $class
 * @param array $attributes
 * @param int $times
 * @return mixed
 */
function create_factory($class, $attributes = [], $times = null)
{
    return factory($class, $times)->create($attributes);
}


/**
 * Make a model factory
 *
 * @param $class
 * @param array $attributes
 * @param int $times
 * @return mixed
 */
function make_factory($class, $attributes = [], $times = null)
{
    return factory($class, $times)->make($attributes);
}

