<?php

namespace Tamdaz\Doc2Html;

use Exception;
use Reflection;
use DOMDocument;
use DOMException;
use ReflectionClass;
use ReflectionMethod;
use Barryvdh\Reflection\DocBlock;

/**
 * Class that allows to convert PHP documentations in HTML file.
 */
class DocumentationRenderer
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

        // To avoid that "DOMDocument" class triggers a warning because of HTML5 semantic tags,
        // an arobase (@) is specified during HTML file is loading.
        @$this->dom->loadHTMLFile(__DIR__ . '/../templates/empty-doc.html');

        $this->renderListOfClasses();
        $this->renderDocumentationFromMethods();
        $this->renderListOfProperties();
        $this->renderListOfMethods();

        $this->dom->saveHTMLFile($outputPath);
    }

    /**
     * Get the path where HTML docs will be saved.
     *
     * @return string
     */
    public function getPath(): string
    {
        if (str_ends_with($this->path, "/"))
            return $this->path;

        return $this->path . '/';
    }

    /**
     * Allows to display all classes.
     *
     * @throws DOMException
     * @throws Exception
     */
    private function renderListOfClasses(): void
    {
        $asideLeft = $this->dom->getElementById("asideNamespacesAndClasses");

        $h2 = $this->dom->createElement("h2", "Classes");
        $asideLeft->appendChild($h2);

        // Group of namespaces.
        foreach (Classmap::getInstance()->getGroupNamespacesName() as $namespace => $classes) {
            $div = $this->dom->createElement("div");

            $h3 = $this->dom->createElement("h3", $namespace);
            $div->appendChild($h3);

            $ul = $this->dom->createElement("ul");

            foreach ($classes as $class) {
                $li = $this->dom->createElement("li");
                $a = $this->dom->createElement("a", $class);
                $a->setAttribute("href", $class . ".html");

                $li->appendChild($a);
                $ul->appendChild($li);
            }

            $div->appendChild($ul);
            $asideLeft->appendChild($div);
        }
    }

    /**
     * Build the signature of a method.
     *
     * @param ReflectionMethod $method
     * @return string
     */
    private function buildSignature(ReflectionMethod $method): string
    {
        // Signature
        $modifiers = Reflection::getModifierNames($method->getModifiers());
        $signature = join(" ", $modifiers) . " function " . $method->getName() . "(";

        foreach ($method->getParameters() as $key => $parameter) {
            $signature .= $parameter->getType() . " $" . $parameter->getName();

            if ($key !== array_key_last($method->getParameters()))
                $signature .= ", ";
        }

        if ($method->hasReturnType())
            $signature .= "): " . $method->getReturnType();
        else
            $signature .= ")";

        return $signature;
    }

    /**
     * Allows to display a documentation for each method.
     *
     * @throws DOMException
     */
    private function renderDocumentationFromMethods(): void
    {
        $main = $this->dom->getElementById("mainBlock");

        $title = $this->dom->createElement("h1", $this->class->getShortName());
        $title->setAttribute("style", "font-family: Ubuntu Sans Mono, monospace");

        $main->appendChild($title);

        $description = (new DocBlock($this->class))->getShortDescription();
        $p = $this->dom->createElement("p", $description);
        $main->appendChild($p);

        foreach ($this->class->getMethods() as $method) {
            $docBlock = new DocBlock($method);

            $methodName = $method->getName();
            $returnType = $method->getReturnType();

            $divId = "doc2html_method_" . $methodName;

            $div = $this->dom->createElement("div");
            $div->setAttribute("id", $divId);
            $div->setAttribute("style", "border-bottom: 1px solid lightgrey");

            if (!empty($returnType)) {
                $h2 = $this->dom->createElement("h1", "# $methodName(): $returnType");
            } else {
                $h2 = $this->dom->createElement("h1", "# $methodName()");
            }

            $pre = $this->dom->createElement("pre", $this->buildSignature($method));

            $h2->setAttribute("style", "font-family: Ubuntu Sans Mono, monospace");
            $p = $this->dom->createElement("p", $docBlock->getShortDescription());

            if ($docBlock->hasTag("deprecated")) {
                $deprecationMessage = $docBlock->getTagsByName("deprecated")[0]->getDescription();

                $div->setAttribute("style", "background-color: yellow; padding: 2px 16px 16px 16px");
                $b = $this->dom->createElement("b", "DEPRECATION WARNING : $deprecationMessage");

                $div->append($h2, $pre, $p, $b);
            } else {
                $div->append($h2, $pre, $p);
            }

            $main->appendChild($div);
        }
    }

    /**
     * Render a list of properties in specified class.
     *
     * @throws DOMException
     */
    private function renderListOfProperties(): void
    {
        $asideRight = $this->dom->getElementById("asidePropertiesAndMethods");

        $span = $this->dom->createElement("h2", "Properties");
        $asideRight->appendChild($span);

        $ul = $this->dom->createElement("ul");

        foreach ($this->class->getProperties() as $property) {
            $li = $this->dom->createElement("li");
            $a = $this->dom->createElement("a", $property->getName());
            $a->setAttribute("href", "#doc2html_property_" . $property->getName());

            $li->appendChild($a);
            $ul->appendChild($li);
        }

        $asideRight->appendChild($ul);
    }

    /**
     * Render a list of methods in specified class.
     *
     * @throws DOMException
     */
    private function renderListOfMethods(): void
    {
        $asideRight = $this->dom->getElementById("asidePropertiesAndMethods");

        $span = $this->dom->createElement("h2", "Methods");
        $asideRight->appendChild($span);

        $ul = $this->dom->createElement("ul");

        foreach ($this->class->getMethods() as $method) {
            $li = $this->dom->createElement("li");
            $a = $this->dom->createElement("a", $method->getName());
            $a->setAttribute("href", "#doc2html_method_" . $method->getName());

            $li->appendChild($a);
            $ul->appendChild($li);
        }

        $asideRight->appendChild($ul);
    }
}
