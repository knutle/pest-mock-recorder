<?php

namespace Knutle\MockRecorder;

use Pest\Mock\Mock;

/**
 * @mixin Mock
 */
class MockRecorder
{
    private Mock $mock;

    public array $history = [];

    public function __construct(Mock $mock)
    {
        $this->mock = $mock;
    }

    /**
     * Proxies calls to the original mock object.
     *
     * @param array<int, mixed> $arguments
     */
    public function __call(string $method, array $arguments): mixed
    {
        return $this->mock->{$method}(...$arguments);
    }

    public function expect(callable ...$methods)
    {
        $methods = collect($methods)
            ->map(function ($handler) {
                return function () use ($handler) {
                    $output = $handler(...func_get_args());

                    if (is_scalar($output)) {
                        $record = $output;
                        $return = null;
                    } else {
                        [ $record, $return ] = $output;
                    }

                    $this->history[] = $record;

                    // TODO: default return values when not provided for simple types? ("", 0, etc)
                    // we can determine return type hint of mocked function via reflection probably, so should be possible

                    return $return;
                };
            })
            ->toArray();

        return $this->mock->expect(...$methods);
    }

    public static function bind(string $abstract): MockRecorder
    {
        return new MockRecorder(bind_mock($abstract));
    }
}
