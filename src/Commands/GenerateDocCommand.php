<?php

namespace Tamdaz\Doc2Html\Commands;

use Exception;
use DOMException;
use Tamdaz\Doc2Html\Reflectors;
use Tamdaz\Doc2Html\LoggerOutput;
use Tamdaz\Doc2Html\Attributes\Command;
use Tamdaz\Doc2Html\Enums\CommandStatus;
use Tamdaz\Doc2Html\Exceptions\EmptyClassesException;

#[Command(
    'convert-to-html',
    'Convert PHP documentations into HTML files.'
)]
class GenerateDocCommand extends AbstractCommand
{
    public function execute(): CommandStatus
    {
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
