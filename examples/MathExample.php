<?php

namespace Examples;

/**
 * Allows to do math operations.
 *
 * @author tamdaz
 * @license MIT
 */
class MathExample
{
    /**
     * Do addition of two numbers.
     *
     * @param int $a First int number
     * @param int $b Second int number
     * @return int
     */
    public function add(int $a, int $b): int
    {
        return $a + $b;
    }

    /**
     * Subtract first number by the second.
     *
     * @param int $a First int number
     * @param int $b Second int number
     * @return int
     */
    public function sub(int $a, int $b): int
    {
        return $a - $b;
    }

    /**
     * Multiply two numbers.
     *
     * @param int $a First int number
     * @param int $b Second int number
     * @return int
     */
    public function multiply(int $a, int $b): int
    {
        return $a * $b;
    }

    /**
     * Divide first number by the second.
     *
     * @param int $a First int number
     * @param int $b Second int number
     * @return int
     */
    public function divide(int $a, int $b): int
    {
        return $a * $b;
    }
}