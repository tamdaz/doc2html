<?php

namespace Tamdaz\Doc2Html;

use Exception;
use ReflectionClass;
use HaydenPierce\ClassFinder\ClassFinder;

/**
 * Class that store namespaces and classes for generate documentations.
 */
class ObjectRegister
{
    /**
     * @var ObjectRegister|null
     */
    private static ?ObjectRegister $instance = null;

    /**
     * @var array<ReflectionClass>
     */
    private array $classes = [];

    /**
     * @var array<string>
     */
    private array $namespaces = [];

    /**
     * Get or create the instance of himself.
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null)
            self::$instance = new ObjectRegister();

        return self::$instance;
    }

    /**
     * Allows to register a namespace.
     *
     * @param string $namespace
     * @return void
     */
    public function registerNamespace(string $namespace): void
    {
        $this->namespaces[] = $namespace;
    }

    /**
     * Find classes for each namespace.
     *
     * All found classes are registered in $classes property.
     *
     * @return void
     * @throws Exception
     */
    public function findClasses(): void
    {
        ClassFinder::disablePSR4Vendors();

        foreach ($this->getNamespaces() as $namespace) {
            LoggerOutput::info("Found classes in $namespace\n");

            $classes = ClassFinder::getClassesInNamespace($namespace);

            foreach ($classes as $class) {
                $this->addClass(new ReflectionClass($class));

                LoggerOutput::success("Registered $class class\n");
            }
        }
    }

    /**
     * Get all registered namespaces.
     *
     * @return array<string>
     */
    public function getNamespaces(): array
    {
        return $this->namespaces;
    }

    /**
     * Get all found classes.
     *
     * @return array<ReflectionClass>
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * Add a class.
     *
     * @param ReflectionClass $class
     * @return void
     */
    public function addClass(ReflectionClass $class): void
    {
        $this->classes[] = $class;
    }
}