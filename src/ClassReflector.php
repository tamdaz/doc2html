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

        if (empty($objectRegister->getNamespaces()))
            die("There's no registered namespaces, end of program.");

        $path = __DIR__ . "/../output/";

        foreach ($objectRegister->getClasses() as $class)
            (new DocumentationRenderer($class, $path))->render();
    }
}