<?php

namespace Tamdaz\Doc2web;

use Barryvdh\Reflection\DocBlock;
use DOMDocument;
use DOMException;
use Exception;
use HaydenPierce\ClassFinder\ClassFinder;
use ReflectionClass;

class DocumentRenderer
{
    /**
     * @var DOMDocument
     */
    private DOMDocument $dom;

    /**
     * @param ReflectionClass $class
     * @param string $path
     */
    public function __construct(
        private ReflectionClass $class,
        private string $path
    ) {
        $this->dom = new DOMDocument("1.0");
    }

    /**
     * Render all documentation in specified classes as HTML files.
     *
     * @throws DOMException
     */
    public function render(): void
    {
        $outputPath = $this->getPath() . $this->class->getShortName() . ".html";

        // To avoid that "DOMDocument" class triggers a warning because of HTML5 tags,
        // an arobase (@) is specified during HTML file is loading.
        @$this->dom->loadHTMLFile(__DIR__ . '/../templates/empty-doc.html');

        $this->generateListOfClasses();
        $this->generateDocMethods();
        $this->generateListOfPropertiesAndMethods();

        $this->dom->saveHTMLFile($outputPath);
    }

    /**
     * Get the path where HTML docs are saved.
     *
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * Allows to display all classes in specific classes.
     * @throws DOMException
     * @throws Exception
     */
    private function generateListOfClasses(): void
    {
        global $selectedNamespace;

        $asideLeft = $this->dom->getElementById("asideNamespacesAndClasses");

        $h2 = $this->dom->createElement("h2", "Classes");
        $asideLeft->appendChild($h2);

        // All classes in specified namespace.
        $ul = $this->dom->createElement("ul");

        $classesInNamespace = ClassFinder::getClassesInNamespace($selectedNamespace);

        foreach ($classesInNamespace as $class) {
            $className = last(explode("\\", $class));

            $li = $this->dom->createElement("li");
            $a = $this->dom->createElement("a", $class);
            $a->setAttribute("href", $className . ".html");

            $li->appendChild($a);
            $ul->appendChild($li);
        }

        $asideLeft->appendChild($ul);
    }

    /**
     * Allows to display all information for each method in a specific class.
     *
     * @throws DOMException
     */
    private function generateDocMethods(): void
    {
        $main = $this->dom->getElementById("mainBlock");

        foreach ($this->class->getMethods() as $method) {
            $docBlock = new DocBlock($method);

            $methodName = $method->getName();
            $returnType = $method->getReturnType();
            $divId = "doc2web_method_" . $methodName;

            $div = $this->dom->createElement("div");
            $div->setAttribute("id", $divId);
            $h2 = $this->dom->createElement("h1", "$methodName() -> $returnType");
            $h2->setAttribute("style", "font-family: Ubuntu Sans Mono, monospace;");

            $p = $this->dom->createElement("p", $docBlock->getShortDescription());

            $div->append($h2, $p);
            $main->appendChild($div);
        }
    }

    /**
     * Allows to generate a list of properties and methods in a class.
     *
     * @throws DOMException
     */
    private function generateListOfPropertiesAndMethods(): void
    {
        $asideRight = $this->dom->getElementById("asidePropertiesAndMethods");

        $span = $this->dom->createElement("h2", "Props and methods");
        $asideRight->appendChild($span);

        // All properties.
        $ul = $this->dom->createElement("ul");

        foreach ($this->class->getProperties() as $property) {
            $li = $this->dom->createElement("li");
            $a = $this->dom->createElement("a", $property->getName());
            $a->setAttribute("href", "#doc2web_property_" . $property->getName());

            $li->appendChild($a);
            $ul->appendChild($li);
        }

        $asideRight->appendChild($ul);

        // All methods.
        $ul = $this->dom->createElement("ul");

        foreach ($this->class->getMethods() as $method) {
            $li = $this->dom->createElement("li");
            $a = $this->dom->createElement("a", $method->getName());
            $a->setAttribute("href", "#doc2web_method_" . $method->getName());

            $li->appendChild($a);
            $ul->appendChild($li);
        }

        $asideRight->appendChild($ul);
    }
}
