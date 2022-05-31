<?php

namespace Knutle\MockRecorder\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Orchestra\Testbench\TestCase as Orchestra;
use Knutle\MockRecorder\MockRecorderServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            MockRecorderServiceProvider::class,
        ];
    }
}
