<?php

namespace Tamdaz\Doc2Html;

/**
 * Allows to display information in standard output.
 */
class LoggerOutput
{
    /**
     * @var string
     */
    private const string DEFAULT_COLOR = "\e[39m";

    /**
     * @var string
     */
    private const string INFO_COLOR = "\e[34m";

    /**
     * @var string
     */
    private const string SUCCESS_COLOR = "\e[32m";

    /**
     * @var string
     */
    private const string WARNING_COLOR = "\e[33m";

    /**
     * @var string
     */
    private const string ERROR_COLOR = "\e[31m";

    /**
     * Display an info output.
     *
     * @param string $value
     * @return void
     */
    public static function info(string $value): void
    {
        echo "[" . self::INFO_COLOR . "INFO" . self::DEFAULT_COLOR . "] $value";
    }

    /**
     * Display a success output.
     *
     * @param string $value
     * @return void
     */
    public static function success(string $value): void
    {
        echo "[ " . self::SUCCESS_COLOR . "OK" . self::DEFAULT_COLOR . " ] $value";
    }

    /**
     * Display a warning output.
     *
     * @param string $value
     * @return void
     */
    public static function warning(string $value): void
    {
        echo "[" . self::WARNING_COLOR . "WARN" . self::DEFAULT_COLOR . "] $value";
    }

    /**
     * Display an error output.
     *
     * @param string $value
     * @return void
     */
    public static function error(string $value): void
    {
        echo "[ " . self::ERROR_COLOR . "!!" . self::DEFAULT_COLOR . " ] $value";
    }

    /**
     * Display a progress output.
     *
     * @param string $value
     * @return void
     */
    public static function progress(string $value): void
    {
        echo "[ " . self::INFO_COLOR . ">>" . self::DEFAULT_COLOR . " ] $value";
    }
}