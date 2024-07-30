<?php

namespace Tamdaz\Doc2Html\Commands;

use Tamdaz\Doc2Html\LoggerOutput;
use Tamdaz\Doc2Html\Attributes\Command;
use Tamdaz\Doc2Html\Enums\CommandStatus;

#[Command(
    'generate-config',
    'Generate a configuration file.'
)]
class GenerateConfigCommand extends AbstractCommand
{
    public function execute(): CommandStatus
    {
        copy(
            __DIR__ . '/../../generators/doc2html.config.php',
            './doc2html.config.php'
        );

        LoggerOutput::success("Successfully generated \"doc2html.config.php\" file.\n");

        return CommandStatus::SUCCESS;
    }
}