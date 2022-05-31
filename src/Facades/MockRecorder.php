<?php

namespace Knutle\MockRecorder\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Knutle\MockRecorder\MockRecorder
 */
class MockRecorder extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'pest-mock-recorder';
    }
}
