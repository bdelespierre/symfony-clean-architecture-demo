<?php

if (! function_exists('tap')) {
    /**
     * Executes $callback, passing it $value, then return $value.
     *
     * @param  mixed  $value
     * @param  callable  $callback
     * @return mixed
     */
    function tap($value, callable $callback)
    {
        $callback($value);

        return $value;
    }
}
