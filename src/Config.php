<?php

namespace Tamdaz\Doc2Html;

class Config
{
    /**
     * Get the config from this library.
     *
     * @info This config path is stored as property to keep it absolute (for dev mode only).
     * @var string
     */
    private static string $DEV_CONFIG_PATH = __DIR__ . '/../doc2html.config.php';

    /**
     * Get the config from this library.
     *
     * @info This config path is stored as property to keep it absolute (for test mode only).
     * @var string
     */
    private static string $TEST_CONFIG_PATH = __DIR__ . '/../test.doc2html.config.php';

    /**
     * Get the config from the project.
     *
     * @info This config path is stored as property to keep it absolute.
     * @var string
     */
    private static string $CONFIG_PATH = __DIR__ . '/../../../../doc2html.config.php';

    /**
     * @var string
     * @psalm-var "dev"|"test"|"prod"
     */
    public static string $envMode = "prod";

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
     * Allows to get the PHP config named "config.php".
     *
     * @return object
     */
    public static function getConfig(): object
    {
        return match (self::$envMode) {
            "dev" => (object) require self::$DEV_CONFIG_PATH,
            "test" => (object) require self::$TEST_CONFIG_PATH,
            "prod" => (object) require self::$CONFIG_PATH
        };
    }
}