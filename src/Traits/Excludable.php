<?php

namespace Tamdaz\Doc2Html\Traits;

use Tamdaz\Doc2Html\Classmap;

trait Excludable
{
    /**
     * @var array<string>
     */
    private array $excludedNamespaces = [];

    /**
     * @var array<string>
     */
    private array $excludedClasses = [];

    /**
     * @return array<string>
     */
    public function getExcludedNamespaces(): array
    {
        return $this->excludedNamespaces;
    }

    /**
     * @param string ...$namespaces
     * @return Classmap
     */
    public function excludeNamespace(string ...$namespaces): Classmap
    {
        foreach ($namespaces as $namespace)
            $this->excludedNamespaces[] = $namespace;

        return $this;
    }

    /**
     * @return array
     */
    public function getExcludedClasses(): array
    {
        return $this->excludedClasses;
    }

    /**
     * @param string ...$classes
     * @return Classmap
     */
    public function excludeClasses(string ...$classes): Classmap
    {
        $this->excludedClasses = $classes;

        return $this;
    }
}