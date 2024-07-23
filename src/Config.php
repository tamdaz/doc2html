<?php

namespace Tamdaz\Doc2Html;

class Config
{
    /**
     * Get the config from the project.
     *
     * @info This config path is stored as property to keep it absolute.
     * @var string
     */
    private const string CONFIG_PATH = __DIR__ . '/../../../../doc2html.config.php';

    /**
     * Get the config from this library.
     *
     * @info This config path is stored as property to keep it absolute (for dev mode only).
     * @var string
     */
    private const string DEV_CONFIG_PATH = __DIR__ . '/../doc2html.config.php';

    /**
     * @var bool
     */
    public static bool $isDevMode = false;

    /**
     * Get the output path where documentations will be saved.
     *
     * @return string
     */
    public static function getOutputDir(): string
    {
        return self::getConfig()->output_dir;
    }

    /**
     * Check if the program can be verbose or not.
     *
     * @return bool
     */
    public static function isVerbose(): bool
    {
        return self::getConfig()->verbose;
    }

    /**
     * @return array<string>
     */
    public static function includeNamespaces(): array
    {
        return self::getConfig()->include_namespaces;
    }

    /**
     * @return array<string>
     */
    public static function excludeNamespaces(): array
    {
        return self::getConfig()->exclude_namespaces;
    }

    /**
     * @return array<string>
     */
    public static function includeClasses(): array
    {
        return self::getConfig()->include_classes;
    }

    /**
     * @return array<string>
     */
    public static function excludeClasses(): array
    {
        return self::getConfig()->exclude_classes;
    }

    /**
     * Get the output path where documentations will be saved.
     *
     * @return array<string>
     */
    public static function getTargetNamespaces(): array
    {
        return self::getConfig()->target_namespaces;
    }

    /**
     * Allows to get the PHP config named "config.php".
     *
     * @return object
     */
    private static function getConfig(): object
    {
        if (self::$isDevMode === true)
            return (object) require self::DEV_CONFIG_PATH;
        else
            return (object) require self::CONFIG_PATH;
    }
}