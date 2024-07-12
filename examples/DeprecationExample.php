<?php

namespace Examples;

/**
 * Class that contains one of two deprecated methods.
 */
class DeprecationExample
{
    /**
     * Old method that displays "Deprecated method".
     *
     * @return string
     * @deprecated use newMethod() instead.
     */
    public function deprecatedMethod(): string
    {
        return "Deprecated method";
    }

    /**
     * New method that displays "New method".
     *
     * @return string
     */
    public function newMethod(): string
    {
        return "New method";
    }
}