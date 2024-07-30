<?php

namespace Tamdaz\Doc2Html\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class Command
{
    /**
     * @param string $name
     * @param string $description
     * @param array<int, string>|null $alias
     */
    public function __construct(
        private string $name,
        private string $description,
        private ?array $alias = null
    ) {}

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return array<int, string>|null
     */
    public function getAlias(): ?array
    {
        return $this->alias;
    }
}