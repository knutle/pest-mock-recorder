<?php

use Pest\Mock\Mock;

function bind_mock(string $abstract): Mock
{
    $mock = mock($abstract);

    app()->bind($abstract, fn () => $mock);

    return $mock;
}
