<?php

namespace Tamdaz\Doc2Html\Exceptions;

use Exception;

/**
 * Exception that designate that namespace can't be found because of incorrect path or name.
 */
class NonExistentNamespaceException extends Exception
{
    /**
     * @var string
     */
    protected $message = "Can't find namespace because the path is not valid or not existent.";

    /**
     * @var string
     */
    protected $code = "D2H-ERR-2";
}
