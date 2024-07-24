<?php

namespace Tamdaz\Doc2Html;

use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use ReflectionException;
use Tamdaz\Doc2Html\Traits\{Excludable, Includable};

class Classmap
{
    use Includable, Excludable;

    /**
     * @var array<int, ReflectionClass<ReflectionMethod|ReflectionProperty>>
     */
    private array $classes = [];

    /**
     * @var array<string, ReflectionClass<ReflectionMethod|ReflectionProperty>>
     */
    private array $groupNamespacesName = [];

    /**
     * @var Classmap|null
     */
    private static ?self $_instance = null;

    /**
     * @return Classmap|null
     */
    public static function getInstance(): Classmap|null
    {
        if (self::$_instance === null)
            self::$_instance = new Classmap();

        return self::$_instance;
    }

    /**
     * Generate classmap. This method uses classmap from Composer (autoload_classmap.php).
     *
     * @return void
     * @throws ReflectionException
     */
    public function generate(): void
    {
        // Dev mode -> 1 time dot...
        // Prod mode -> 3 times dot...
        if (Config::$isDevMode === true)
            $autoloadClassmap = require __DIR__ . '/../vendor/composer/autoload_classmap.php';
        else
            $autoloadClassmap = require __DIR__ . '/../../../composer/autoload_classmap.php';

        // Exclude anything in "vendor/" directory.
        if (!$this->isVendorIncluded()) {
            $classmap = array_filter(
                $autoloadClassmap,
                fn ($v) => !str_contains($v, '/vendor/')
            );
        } else {
            $classmap = $autoloadClassmap;
        }

        $classmap = array_keys($classmap);
        $classmapToMerge = [];

        // These instructions contain 4 steps. They are used to select classes and namespaces.
        // To do this, the program must first exclude them, then add certain classes and
        // namespaces when necessary.
        // This is useful when you want to include sub-namespaces or some classes for example.
        $this->stepExcludeNamespaces($classmap);
        $this->stepIncludeNamespaces($autoloadClassmap, $classmapToMerge);
        $this->stepExcludeClasses($classmap);
        $this->stepIncludeClasses($classmap);

        $classmap = array_merge($classmap, $classmapToMerge);

        $reflectors = [];

        foreach ($classmap as $class) {
            $reflectors[] = (new ReflectionClass($class));
        }

        $this->setClasses($reflectors);
    }

    /**
     * @param array<int, string> $classmap
     * @return void
     */
    private function stepExcludeNamespaces(array &$classmap): void
    {
        if (!empty($this->getExcludedNamespaces())) {
            // Exclude namespaces.
            foreach ($this->getExcludedNamespaces() as $excludedNamespace) {
                $classmap = array_filter(
                    $classmap,
                    fn ($v) => !str_starts_with($v, $excludedNamespace)
                );
            }
        }
    }

    /**
     * @param array<int, string> $classmap
     * @return void
     */
    private function stepExcludeClasses(array &$classmap): void
    {
        if (!empty($this->getExcludedClasses())) {
            // Exclude classes.
            foreach ($this->getExcludedClasses() as $excludedClass) {
                $classmap = array_filter(
                    $classmap,
                    fn ($v) => !str_contains($v, $excludedClass)
                );
            }
        }
    }

    /**
     * @param array<string, string> $autoloadClassmap
     * @param array<int, string> $classmapToMerge
     * @return void
     */
    private function stepIncludeNamespaces(array $autoloadClassmap, array &$classmapToMerge): void
    {
        if (!empty($this->getIncludedNamespaces())) {
            // Include namespaces after exclusion.
            foreach ($this->getIncludedNamespaces() as $includedNamespace) {
                $classmapToMerge = array_filter(
                    $autoloadClassmap,
                    fn ($k) => str_starts_with($k, $includedNamespace),
                    ARRAY_FILTER_USE_KEY
                );

                $classmapToMerge = array_keys($classmapToMerge);
            }
        }
    }

    /**
     * @param array<int, string> $classmap
     * @return void
     */
    private function stepIncludeClasses(array &$classmap): void
    {
        if (!empty($this->getIncludedClasses())) {
            // Include classes after exclusion.
            foreach ($this->getIncludedClasses() as $includedClass) {
                $classmap[] = $includedClass;
            }
        }
    }

    /**
     * @return array<int, ReflectionClass<ReflectionMethod|ReflectionProperty>>
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    /**
     * @param array<int, ReflectionClass<ReflectionMethod|ReflectionProperty>> $classes
     */
    public function setClasses(array $classes): void
    {
        $this->classes = $classes;
    }

    /**
     * @return array<string, ReflectionClass<ReflectionMethod|ReflectionProperty>>|array<string, array<int, string>>
     */
    public function getGroupNamespacesName(): array
    {
        if (empty($this->groupNamespacesName)) {
            $output = [];

            foreach ($this->getClasses() as $class) {
                $output[$class->getNamespaceName()][] = $class->getShortName();
            }

            return $output;
        }

        return $this->groupNamespacesName;
    }
}