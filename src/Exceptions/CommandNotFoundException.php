<?php

namespace Tamdaz\Doc2Html\Exceptions;

use Exception;

class CommandNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $message = "Command not found. Use the --help option to display all commands.";

    /**
     * @var string
     */
    protected $code = "D2H-ERR-3";
}