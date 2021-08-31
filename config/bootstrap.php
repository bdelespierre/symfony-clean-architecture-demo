<?php

function tap(mixed $value, callable $callback): mixed
{
    $callback($value);

    return $value;
}
