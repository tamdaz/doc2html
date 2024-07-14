<?php

namespace Tamdaz\Doc2Html;

class Config
{
    /**
     * TODO: This config path is stored as property to not change it.
     *
     * @var string
     */
    private const string CONFIG_PATH = __DIR__ . '/../config.php';

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
     * @return string
     */
    public static function isVerbose(): string
    {
        return self::getConfig()->verbose;
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
        return (object) include self::CONFIG_PATH;
    }
}