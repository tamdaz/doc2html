<?php

namespace Tamdaz\Doc2Html;

use Exception;
use ReflectionClass;
use ReflectionException;
use Tamdaz\Doc2Html\Attributes\Command;
use Tamdaz\Doc2Html\Commands\AbstractCommand;
use Tamdaz\Doc2Html\Exceptions\CommandNotFoundException;

final class CommandRegister
{
    /**
     * @var CommandRegister|null
     */
    private static ?CommandRegister $_instance = null;

    /**
     * @var array<int, string>
     */
    private array $filteredClassmapByCommandClasses = [];

    /**
     * @var array<int, string>
     */
    private array $arguments = [];

    /**
     * @var array<string, mixed>
     */
    private array $options = [];

    /**
     * @param array<int, string> $argumentsAndOptions
     */
    public function __construct(array $argumentsAndOptions)
    {
        $this->generateArgumentsAndOptions($argumentsAndOptions);
    }

    /**
     * @param array<int, string>|null $arguments
     * @return CommandRegister
     */
    public static function getInstance(?array $arguments = null): CommandRegister
    {
        if (self::$_instance === null)
            self::$_instance = new CommandRegister($arguments);

        return self::$_instance;
    }

    /**
     * Delete instance of this class
     * @return bool Returns true if instance was deleted, false if not.
     */
    public static function deleteInstance(): bool
    {
        if (self::$_instance !== null) {
            self::$_instance = null;
            return true;
        }

        return false;
    }

    /**
     * @return void
     * @throws ReflectionException
     * @throws CommandNotFoundException
     */
    public function run(): void
    {
        if (isset($this->getOptions()["--help"])) {
            LoggerOutput::info("Doc2Html help: \n");

            /** @var AbstractCommand $class */
            foreach ($this->getFilteredClassmapByCommandClasses() as $class) {
                $reflection = new ReflectionClass($class);

                // Check if attribute is defined on the command class.
                if (isset($reflection->getAttributes(Command::class)[0])) {
                    /** @var Command $attribute */
                    $attribute = $reflection->getAttributes(Command::class)[0]->newInstance();
                    $spaces = str_repeat(" ", 24 - mb_strlen($attribute->getName()));

                    LoggerOutput::info("{$attribute->getName()} $spaces --> {$attribute->getDescription()}\n");
                }
            }
        } else {
            $class = $this->findCorrespondingCommand();

            $commandStatus = (new $class($this->getArguments(), $this->getOptions()))
                ->execute()
            ;

            exit($commandStatus->value);
        }
    }

    /**
     * @return string|AbstractCommand
     * @throws CommandNotFoundException
     * @throws ReflectionException
     */
    private function findCorrespondingCommand(): string|AbstractCommand
    {
        /** @var AbstractCommand $class */
        foreach ($this->getFilteredClassmapByCommandClasses() as $class) {
            $reflection = new ReflectionClass($class);

            // Check if attribute is defined on the command class.
            if (isset($reflection->getAttributes(Command::class)[0])) {
                /** @var Command $attribute */
                $attribute = $reflection->getAttributes(Command::class)[0]->newInstance();

                if (in_array($attribute->getName(), $this->getArguments())) {
                    return $class;
                }
            }
        }

        throw new CommandNotFoundException();
    }

    /**
     * @return array<string, mixed>
     */
    public function getFilteredClassmapByCommandClasses(): array
    {
        if (empty($this->filteredClassmapByCommandClasses)) {
            $classmap = require Classmap::getInstance()->getClassmapPath();

            $classmap = array_filter($classmap, function (string $namespace) {
                return str_starts_with($namespace, "Tamdaz\\Doc2Html\\Commands")
                    && !str_ends_with($namespace, "AbstractCommand");
            }, ARRAY_FILTER_USE_KEY);

            $classmap = array_keys($classmap);

            $this->filteredClassmapByCommandClasses = $classmap;
        }

        return $this->filteredClassmapByCommandClasses;
    }

    /**
     * @return array<int, string>
     */
    public function getArguments(): array
    {
        return $this->arguments;
    }

    /**
     * @return array<string, mixed>
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Generate the arguments and the options.
     *
     * @param array $argumentsAndOptions
     * @return void
     */
    private function generateArgumentsAndOptions(array $argumentsAndOptions): void
    {
        if (Config::$envMode !== "test") {
            // Delete the first argument from array: it's the program name.
            // If we are in the test environment, so it's not necessary to delete it.
            unset($argumentsAndOptions[0]);
        }

        $this->arguments = array_filter($argumentsAndOptions, function (string $argument) {
            return !str_starts_with($argument, "--") && !($argument[0] === "-" && $argument[1] !== "-");
        }, ARRAY_FILTER_USE_BOTH);

        $this->options = array_filter($argumentsAndOptions, function (string $option) {
            return str_starts_with($option, "--") || ($option[0] === "-" && $option[1] !== "-");
        }, ARRAY_FILTER_USE_BOTH);

        $this->arguments = array_values($this->arguments);
        $this->options = array_values($this->options);

        $newOptions = [];

        foreach ($this->options as $option) {
            // Short option
            if ($option[0] === "-" && $option[1] !== "-") {
                $shortOptions = array_diff(str_split(explode("=", $option)[0]), ['-']);

                if (str_contains($option, "=")) {
                    $value = explode("=", $option)[1];

                    foreach ($shortOptions as $shortOption)
                        $newOptions["-" . $shortOption] = $value ??= true;

                    continue;
                }

                foreach ($shortOptions as $shortOption) {
                    $newOptions["-" . $shortOption] = $value ?? true;
                }
            }

            // Long option
            if ($option[0] === "-" && $option[1] === "-") {
                if (!str_contains($option, "=")) {
                    $newOptions[$option] = true;
                    continue;
                }

                [$name, $value] = explode("=", $option);
                $newOptions[$name] = $value ?? true;
            }
        }

        $this->options = $newOptions;
    }
}