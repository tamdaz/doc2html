<?php

namespace Tamdaz\Doc2Html\Exceptions;

use Exception;

/**
 * Exception that indicates the absence of namespaces.
 */
class EmptyNamespacesException extends Exception
{
    /**
     * @var string
     */
    protected $message = "No registered namespace. Please specify it in your config file.";

    /**
     * @var int
     */
    protected $code = "D2H-ERR-1";
}