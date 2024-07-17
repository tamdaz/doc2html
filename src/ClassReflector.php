<?php

namespace Tamdaz\Doc2Html;

use Exception;

class ClassReflector
{
    /**
     * Analyze all classes in specific namespace(s).
     *
     * @throws Exception
     */
    public function run(): void
    {
        $objectRegister = ObjectRegister::getInstance();
        $objectRegister->findClasses();

        if (empty($objectRegister->getNamespaces())) {
            echo "There's no registered namespaces, end of program.\n";
            exit(1);
        }

        $path = Config::getOutputDir();

        foreach ($objectRegister->getClasses() as $class) {
            if (Config::isVerbose())
                LoggerOutput::progress("Generating documentation for {$class->getShortName()} class...\r");

            (new DocumentationRenderer($class, $path))->render();

            if (Config::isVerbose())
                LoggerOutput::success("Generating documentation for {$class->getShortName()} class...\n");
        }

        echo "[ \e[32mOK\e[39m ] Documentation successfully generated !\n";
    }
}