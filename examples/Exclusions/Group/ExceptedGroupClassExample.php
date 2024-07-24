<?php

namespace Examples\Exclusions\Group;

/**
 * NOTE: This class is included, this is an exception.
 */
class ExceptedGroupClassExample
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