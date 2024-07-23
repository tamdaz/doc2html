<?php

namespace Examples\Exclusions;

/**
 * NOTE: This class is included, this is an exception.
 */
class ExceptedClassExample
{
    /**
     * @var bool
     */
    private bool $isExcepted = true;

    /**
     * @return bool
     */
    public function isExcepted(): bool
    {
        return $this->isExcepted;
    }

    /**
     * @param bool $isExcepted
     * @return void
     */
    public function setIsExcepted(bool $isExcepted): void
    {
        $this->isExcepted = $isExcepted;
    }
}