<?php

namespace Tamdaz\Doc2web;

use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use ReflectionClass;
use function Laravel\Prompts\text;

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
//        $path = text(
//            label: "Where do you want to save your documentation?",
//            placeholder: "ex.: /path/to/folder/",
//            hint: "You can also use the relative path."
//        );

        $path = __DIR__ . "/../output/";

        ClassFinder::disablePSR4Vendors();
        $classesInNamespace = ClassFinder::getClassesInNamespace($this->targetNamespace);

        foreach ($classesInNamespace as $classInNamespace)
            $this->classes[] = (new ReflectionClass($classInNamespace));

        foreach ($this->getClasses() as $class)
            (new DocumentRenderer($class, $path))->render();
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