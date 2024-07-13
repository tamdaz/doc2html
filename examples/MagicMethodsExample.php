<?php

namespace Examples;

/**
 * Class that contains magic methods.
 */
class MagicMethodsExample
{
    /**
     * __construct magic method.
     */
    public function __construct()
    {
        // ...
    }

    /**
     * __call magic method.
     *
     * @param string $name
     * @param array $arguments
     *
     * @return void
     */
    public function __call(string $name, array $arguments): void
    {
        // ..
    }

    /**
     * __toString magic method.
     *
     * @return string
     */
    public function __toString(): string
    {
        return __CLASS__;
    }

    /**
     * __clone magic method.
     *
     * @return void
     */
    public function __clone(): void
    {
        // Clone...
    }

    /**
     * __debugInfo magic method.
     *
     * @return array|null
     */
    public function __debugInfo(): ?array
    {
        return [];
    }

    /**
     * __get magic method.
     *
     * @param string $name
     * @return void
     */
    public function __get(string $name): void
    {
        echo $name;
    }

    /**
     * __set magic method.
     *
     * @param string $name
     * @param mixed $value
     * @return void
     */
    public function __set(string $name, mixed $value): void
    {
        echo $name . " " . $value;
    }

    /**
     * __invoke magic method.
     *
     * @return void
     */
    public function __invoke()
    {
        // ...
    }

    /**
     * __isset magic method.
     *
     * @param string $name
     * @return bool
     */
    public function __isset(string $name): bool
    {
        return $name;
    }

    /**
     * __serialize magic method.
     *
     * @return array
     */
    public function __serialize(): array
    {
        return [];
    }

    /**
     * __sleep magic method.
     *
     * @return array
     */
    public function __sleep(): array
    {
        return [];
    }

    /**
     * __unserialize magic method.
     *
     * @param array $data
     * @return void
     */
    public function __unserialize(array $data): void
    {
        // ...
    }

    /**
     * __unset magic method.
     *
     * @param string $name
     * @return void
     */
    public function __unset(string $name): void
    {
        echo $name;
    }

    /**
     * __wakeup magic method.
     *
     * @return void
     */
    public function __wakeup(): void
    {
        // ...
    }

    /**
     * __callStatic magic method.
     *
     * @param string $name
     * @param array $arguments
     * @return void
     */
    public static function __callStatic(string $name, array $arguments)
    {
        // ...
    }

    /**
     * __set_state magic method.
     *
     * @param array $an_array
     * @return object
     */
    public static function __set_state(array $an_array): object
    {
        return (object) [$an_array];
    }

    /**
     * __destruct magic method.
     */
    public function __destruct()
    {
        return "Class destroyed.";
    }
}