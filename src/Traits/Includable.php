<?php

namespace Tamdaz\Doc2Html\Traits;

use Tamdaz\Doc2Html\Classmap;

trait Includable
{
    /**
     * @var array<string>
     */
    private array $includedNamespaces = [];

    /**
     * @var array<string>
     */
    private array $includedClasses = [];

    /**
     * @var bool
     */
    private bool $includeVendor = false;


    /**
     * @return array
     */
    public function getIncludedNamespaces(): array
    {
        return $this->includedNamespaces;
    }

    /**
     * @param string ...$namespaces
     * @return Classmap
     */
    public function includeNamespaces(string ...$namespaces): Classmap
    {
        foreach ($namespaces as $namespace)
            $this->includedNamespaces[] = $namespace;

        return $this;
    }

    /**
     * @return array
     */
    public function getIncludedClasses(): array
    {
        return $this->includedClasses;
    }

    /**
     * @param string ...$classes
     * @return Classmap
     */
    public function includeClasses(string ...$classes): Classmap
    {
        foreach ($classes as $class)
            $this->includedClasses[] = $class;

        return $this;
    }

    /**
     * @return bool
     */
    public function isVendorIncluded(): bool
    {
        return $this->includeVendor;
    }

    /**
     * Please do not use it in production mode.
     *
     * @param bool $includeVendor
     * @return Classmap
     */
    public function setIncludeVendor(bool $includeVendor): Classmap
    {
        $this->includeVendor = $includeVendor;

        return $this;
    }
}