<?php

namespace Tamdaz\Doc2Html\Commands;

use Tamdaz\Doc2Html\Enums\CommandStatus;

abstract class AbstractCommand
{
    /**
     * @param array<int, string> $arguments
     * @param array<string, mixed> $options
     */
    public function __construct(
        private array $arguments,
        private array $options,
    ) {}

    /**
     * Execute a command.
     *
     * @return CommandStatus
     */
    abstract public function execute(): CommandStatus;

    /**
     * Get all options.
     *
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * @return array<int, string>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }
}