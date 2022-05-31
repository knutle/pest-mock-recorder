<?php

namespace Knutle\MockRecorder\Tests\Fakes;

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
}
