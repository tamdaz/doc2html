<?php

namespace Tamdaz\Doc2web;

use Barryvdh\Reflection\DocBlock;
use DOMDocument;
use DOMException;
use ReflectionClass;
use ReflectionMethod;

class DocumentRenderer
{
    public function __construct(
        private ReflectionClass $class
    ) {}

    /**
     * Generate a list of properties and methods present in a class.
     *
     * @throws DOMException
     */
    private function generateList(DOMDocument $dom): void
    {
        // List of props.
        $ul = $dom->createElement("ul");
        $ul = $dom->appendChild($ul);

        foreach ($this->class->getProperties() as $property) {
            $a = $dom->createElement("a", $property->getName());
            $a->setAttribute("href", "#doc2web_properties_" . $property->getName());

            $li = $dom->createElement("li");
            $li->appendChild($a);

            $ul->appendChild($li);
        }

        // List of methods.
        $ul = $dom->createElement("ul");
        $ul = $dom->appendChild($ul);

        foreach ($this->class->getMethods() as $method) {
            $a = $dom->createElement("a", $method->getName());
            $a->setAttribute("href", "#doc2web_method_" . $method->getName());

            $li = $dom->createElement("li");
            $li->appendChild($a);

            $ul->appendChild($li);
        }
    }

    /**
     * @throws DOMException
     */
    private function displayMethod(DOMDocument $dom, ReflectionMethod $method): void
    {
        $docBlock = new DocBlock($method);

        $div = $dom->createElement("div");

        if ($docBlock->hasTag("deprecated"))
            $div->setAttribute("style", "color: red");

        $div = $dom->appendChild($div);

        $h2 = $dom->createElement("h2", "{$method->getName()}(): {$method->getReturnType()}");
        $h2->setAttribute("id", "doc2web_method_" . $method->getName());
        $h2->setAttribute("style", "font-family: monospace");

        $div->appendChild($h2);

        $i = $dom->createElement("p", $docBlock->getShortDescription());
        $div->appendChild($i);

        if ($docBlock->hasTag("deprecated")) {
            $deprecationMessage = $docBlock->getTagsByName("deprecated")[0]->getDescription();

            $i = $dom->createElement("b", "Deprecation warning: $deprecationMessage");
            $div->appendChild($i);
        }
    }

    /**
     * Render documentations as HTML file.
     *
     * @throws DOMException
     */
    public function render(): void
    {
        $dom = new DOMDocument('1.0');
        $dom->formatOutput = true;

        $style = $dom->createElement("style", "body { max-width: 632px; margin: 0 auto; padding: 32px }");
        $dom->appendChild($style);

        // Namespace location
        $pre = $dom->createElement("pre", $this->class->getName());
        $dom->appendChild($pre);

        // Class name.
        $h1 = $dom->createElement("h1", $this->class->getShortName());
        $dom->appendChild($h1);

        // List of props & methods.
        $this->generateList($dom);

        $hr = $dom->createElement("hr");
        $dom->appendChild($hr);

        // Methods.
        foreach ($this->class->getMethods() as $method) {
            $this->displayMethod($dom, $method);
        }

        $dom->saveHTMLFile(__DIR__ . "/../output/{$this->class->getShortName()}.html");
    }
}