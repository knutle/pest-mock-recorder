<?php

/** @noinspection PhpUnhandledExceptionInspection */

use Knutle\MockRecorder\MockRecorder;
use Knutle\MockRecorder\Tests\Fakes\GreetingGenerator;

it('can record from and return data for mocked function with return type hint', function () {
    expect(app(GreetingGenerator::class)->greet('Bob'))
        ->toEqual('Hello Bob!');

    $mock = MockRecorder::bind(GreetingGenerator::class);

    $mock->expect(
        greet: fn (string $name) => [ $name, "Poor $name - immediately mocked!" ]
    );

    expect(app(GreetingGenerator::class)->greet('Bob'))->toEqual('Poor Bob - immediately mocked!')
        ->and(app(GreetingGenerator::class)->greet('Jim'))->toEqual('Poor Jim - immediately mocked!')
        ->and($mock->history)->toEqual([
            'Bob',
            'Jim',
        ]);
});

it('will fail on no return data when mocked function requires it', function () {
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

    app(GreetingGenerator::class)->write('Bob');
    app(GreetingGenerator::class)->write('Jim');

    expect($mock->history)->toEqual([
        'Bob',
        'Jim',
    ]);
});

it('can proxy calls to underlying mock', function () {
    $mock = MockRecorder::bind(GreetingGenerator::class);

    $mock->shouldReceive('write')->andReturn('result');

    app(GreetingGenerator::class)->write('Jim');
});

it('can use fallback values when mocked method requires return type but no value was provided', function () {
    $mock = MockRecorder::bind(GreetingGenerator::class)->withMockedMethodReturnValueDefaults();

    $mock->expect(
        greet: fn (string $name) => $name
    );

    expect(app(GreetingGenerator::class)->greet('test'))->toEqual('');
});

it('can throw helpful exception when mocked method requires unsupported return type but no value was provided', function () {
    $mock = MockRecorder::bind(GreetingGenerator::class)->withMockedMethodReturnValueDefaults();

    $mock->expect(
        complain: fn (string $complaint) => $complaint
    );

    app(GreetingGenerator::class)->complain('test');
})->throws("Failed to create empty return value of type 'Exception' for mocked method 'complain' on 'Knutle\MockRecorder\Tests\Fakes\GreetingGenerator'. Please provide a return value during your mock setup.");

it('can get instance of underlying mock', function () {
    $mock = MockRecorder::mock(GreetingGenerator::class);

    $mock->shouldReceive('write')->andReturn('result');

    expect($mock->getMock())
        ->toBeInstanceOf(GreetingGenerator::class)
        ->and($mock)
        ->not()
        ->toBeInstanceOf(GreetingGenerator::class);
});
