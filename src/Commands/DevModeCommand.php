<?php

namespace Tamdaz\Doc2Html\Commands;

use Exception;
use DOMException;
use Tamdaz\Doc2Html\Config;
use Tamdaz\Doc2Html\Reflectors;
use Tamdaz\Doc2Html\LoggerOutput;
use Tamdaz\Doc2Html\Attributes\Command;
use Tamdaz\Doc2Html\Enums\CommandStatus;
use Tamdaz\Doc2Html\Exceptions\EmptyClassesException;

#[Command(
    'dev-mode',
    'Generate command in development mode.'
)]
class DevModeCommand extends AbstractCommand
{
    public function execute(): CommandStatus
    {
        Config::$envMode = "dev";

        $reflector = new Reflectors();

        try {
            $reflector->run();
        } catch (EmptyClassesException|DOMException|Exception $e) {
            LoggerOutput::error("[{$e->getCode()}]: {$e->getMessage()}\n");

            return CommandStatus::ERROR;
        }

        return CommandStatus::SUCCESS;
    }
}