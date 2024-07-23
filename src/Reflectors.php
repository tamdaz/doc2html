<?php

namespace Tamdaz\Doc2Html;

use Exception;
use DOMException;
use Tamdaz\Doc2Html\Exceptions\EmptyNamespacesException;

class Reflectors
{
    /**
     * Analyze all classes in specific namespace(s).
     *
     * @throws EmptyNamespacesException
     * @throws DOMException
     * @throws Exception
     */
    public function run(): void
    {
        $classmap = Classmap::getInstance();

        $classmap
            ->excludeNamespace(...Config::excludeNamespaces())
            ->excludeClasses(...Config::excludeClasses())
            ->includeNamespaces(...Config::includeNamespaces())
            ->includeClasses(...Config::includeClasses())
        ;

        if (Config::isVerbose()) {
            LoggerOutput::info("Number of excluded namespace(s) : " . count(Config::excludeNamespaces()) . "\n");
            LoggerOutput::info("Number of excluded class(es)    : " . count(Config::excludeClasses()) . "\n");
            LoggerOutput::info("Number of included namespace(s) : " . count(Config::includeNamespaces()) . "\n");
            LoggerOutput::info("Number of included class(es)    : " . count(Config::includeClasses()) . "\n");
        }

        $classmap->generate();

        if (empty($classmap->getClasses()))
            throw new EmptyNamespacesException();

        $path = Config::getOutputDir();

        foreach ($classmap->getClasses() as $class) {
            if (Config::isVerbose())
                LoggerOutput::progress("Generating documentation for {$class->getShortName()} class in HTML file...\r");

            (new DocumentationRenderer($class, $path))->render();

            if (Config::isVerbose())
                LoggerOutput::success("Generating documentation for {$class->getShortName()} class in HTML file...\n");
        }

        LoggerOutput::success("Documentation successfully generated !\n");
    }
}