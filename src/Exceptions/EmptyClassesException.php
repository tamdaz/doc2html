<?php

namespace Tamdaz\Doc2Html\Exceptions;

use Exception;

class EmptyClassesException extends Exception
{
    /**
     * @var string
     */
    protected $message = "No classes are selected to generate documentation. Please include the classes.";

    /**
     * @var string
     */
    protected $code = "D2H-ERR-1";
}