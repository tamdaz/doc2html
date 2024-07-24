<?php

namespace Tamdaz\Doc2Html\Enums;

/**
 * Contains colors for coloring text in the terminal.
 */
enum LogType: string
{
    case DEFAULT_COLOR = "\e[39m";

    case INFO_COLOR = "\e[34m";

    case SUCCESS_COLOR = "\e[32m";

    case WARNING_COLOR = "\e[33m";

    case ERROR_COLOR = "\e[31m";
}
