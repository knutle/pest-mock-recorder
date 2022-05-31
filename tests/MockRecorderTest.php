<?php

use Knutle\MockRecorder\MockRecorder;
use Knutle\MockRecorder\Tests\Fakes\GreetingGenerator;

it('can record from and return data for mocked function with return type hint', function () {
    expect(app(GreetingGenerator::class)->greet('Bob'))
        ->toEqual('Hello Bob!');

    $mock = MockRecorder::bind(GreetingGenerator::class);

    $mock->expect(
        greet: fn (string $name) => [ $name, "Poor $name - immediately mocked!" ]
    );

    expect(app(GreetingGenerator::class)->greet('Bob'))->toEqual('Poor Bob - immediately mocked!');
    expect(app(GreetingGenerator::class)->greet('Jim'))->toEqual('Poor Jim - immediately mocked!');

    expect($mock->history)->toEqual([
        'Bob',
        'Jim',
    ]);
});

it('will fail if trying to skip return data when function has return type hint', function () {
    $mock = MockRecorder::bind(GreetingGenerator::class);

    $mock->expect(
        greet: fn (string $name) => $name
    );

    expect(app(GreetingGenerator::class)->greet('Bob'))->toEqual('Poor Bob - immediately mocked!');
})->throws('Mockery_0_Knutle_MockRecorder_Tests_Fakes_GreetingGenerator::greet(): Return value must be of type string, null returned');

it('can skip return data for mocked function without return type hint', function () {
    $mock = MockRecorder::bind(GreetingGenerator::class);

    $mock->expect(
        write: fn (string $name) => $name
    );

    expect(app(GreetingGenerator::class)->write('Bob'))->toEqual(null);
    expect(app(GreetingGenerator::class)->write('Jim'))->toEqual(null);

    expect($mock->history)->toEqual([
        'Bob',
        'Jim',
    ]);
});
