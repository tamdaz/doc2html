<?php

namespace Tamdaz\Doc2Html;

use Tamdaz\Doc2Html\Enums\LogType;

/**
 * Allows to display information to standard output (STDOUT).
 */
class LoggerOutput
{
    /**
     * Display an info output.
     *
     * @param string $value
     * @return void
     */
    public static function info(string $value): void
    {
        echo "[" . LogType::INFO_COLOR->value . "INFO" . LogType::DEFAULT_COLOR->value . "] $value";
    }

    /**
     * Display a success output.
     *
     * @param string $value
     * @return void
     */
    public static function success(string $value): void
    {
        echo "[ " . LogType::SUCCESS_COLOR->value . "OK" . LogType::DEFAULT_COLOR->value . " ] $value";
    }

    /**
     * Display a warning output.
     *
     * @param string $value
     * @return void
     */
    public static function warning(string $value): void
    {
        echo "[" . LogType::WARNING_COLOR->value . "WARN" . LogType::DEFAULT_COLOR->value . "] $value";
    }

    /**
     * Display an error output.
     *
     * @param string $value
     * @return void
     */
    public static function error(string $value): void
    {
        echo "[ " . LogType::ERROR_COLOR->value . "!!" . LogType::DEFAULT_COLOR->value . " ] $value";
    }

    /**
     * Display a progress output.
     *
     * @param string $value
     * @return void
     */
    public static function progress(string $value): void
    {
        echo "[ " . LogType::INFO_COLOR->value . ">>" . LogType::DEFAULT_COLOR->value . " ] $value";
    }
}