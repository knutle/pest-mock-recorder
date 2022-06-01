<?php

namespace Knutle\MockRecorder;

use Exception;
use Mockery\MockInterface;
use Pest\Mock\Mock;
use ReflectionClass;
use ReflectionException;
use ReflectionNamedType;
use Throwable;

/**
 * @mixin MockInterface
 */
class MockRecorder
{
    private Mock $mock;

    public array $history = [];
    public ReflectionClass $reflectionClass;
    private bool $shouldUseMockedMethodReturnValueDefaults = false;

    public function __construct(Mock $mock, ReflectionClass $reflectionClass)
    {
        $this->mock = $mock;
        $this->reflectionClass = $reflectionClass;
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
            ->map(function (callable $handler, string $name) {
                return function () use ($handler, $name) {
                    $output = $handler(...func_get_args());

                    if (is_scalar($output)) {
                        $record = $output;
                        $return = null;
                    } else {
                        [ $record, $return ] = $output;
                    }

                    $this->history[] = $record;

                    if ($this->shouldUseMockedMethodReturnValueDefaults) {
                        $mockedMethodReturnType = $this->reflectionClass->getMethod($name)->getReturnType();

                        if (! $mockedMethodReturnType->allowsNull() && is_null($return) && $mockedMethodReturnType instanceof ReflectionNamedType) {
                            $type = $mockedMethodReturnType->getName();

                            try {
                                settype($return, $type);
                            } catch (Throwable) {
                                throw new Exception("Failed to create empty return value of type '$type' for mocked method '$name' on '{$this->reflectionClass->name}'. Please provide a return value during your mock setup.");
                            }
                        }
                    }

                    return $return;
                };
            })
            ->toArray();

        return $this->mock->expect(...$methods);
    }

    /**
     * If the mocked method requires a return value that is not null, and no such value was provided when the
     * expectation was defined, try to cast the null to the required type if possible. Only works for types covered by
     * settype function
     *
     * @see settype()
     *
     * @param bool $shouldUseMockedMethodReturnValueDefaults
     * @return $this
     */
    public function withMockedMethodReturnValueDefaults(bool $shouldUseMockedMethodReturnValueDefaults = true): MockRecorder
    {
        $this->shouldUseMockedMethodReturnValueDefaults = $shouldUseMockedMethodReturnValueDefaults;

        return $this;
    }

    /**
     * @throws ReflectionException
     */
    public static function bind(string $abstract): MockRecorder
    {
        return new MockRecorder(bind_mock($abstract), new ReflectionClass($abstract));
    }
}
