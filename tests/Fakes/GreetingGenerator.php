<?php

namespace Knutle\MockRecorder\Tests\Fakes;

use Exception;

class GreetingGenerator
{
    public function greet(string $name): string
    {
        return "Hello $name!";
    }

    public function write(string $name): void
    {
        echo "Hello $name!";
    }

    public function complain(string $complaint): Exception
    {
        return new Exception('Everything is terrible! ' . $complaint);
    }
}
