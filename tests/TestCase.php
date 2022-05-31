<?php

namespace Knutle\MockRecorder\Tests;

use Knutle\MockRecorder\MockRecorderServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MockRecorderServiceProvider::class,
        ];
    }
}
