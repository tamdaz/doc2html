<?php

namespace Tamdaz\Doc2web;

use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use ReflectionClass;

class ClassReflector
{
    /**
     * @var ReflectionClass[]
     */
    private array $classes = [];

    /**
     * @param string $targetNamespace
     */
    public function __construct(
        private readonly string $targetNamespace
    ) {}

    /**
     * Analyze all classes in specific namespace.
     *
     * @throws Exception
     */
    public function run(): void
    {
        ClassFinder::disablePSR4Vendors();
        $classesInNamespace = ClassFinder::getClassesInNamespace($this->targetNamespace);

        foreach ($classesInNamespace as $classInNamespace)
            $this->classes[] = (new ReflectionClass($classInNamespace));

        foreach ($this->getClasses() as $class)
            (new DocumentRenderer($class))->render();
    }

    /**
     * Get all classes in specific namespace.
     *
     * @return ReflectionClass[]
     */
    public function getClasses(): array
    {
        return $this->classes;
    }
}