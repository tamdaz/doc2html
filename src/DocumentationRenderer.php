<?php

namespace Tamdaz\Doc2Html;

use Exception;
use DOMElement;
use Reflection;
use DOMException;
use ReflectionClass;
use ReflectionMethod;
use ReflectionProperty;
use Barryvdh\Reflection\DocBlock;
use Tamdaz\Doc2Html\Enums\TagType;

/**
 * Render PHPDoc in HTML files.
 */
class DocumentationRenderer extends DOMRenderer
{
    /**
     * @param ReflectionClass<ReflectionMethod|ReflectionProperty> $class
     * @param string $path
     */
    public function __construct(
        private ReflectionClass $class,
        private string $path
    ) {
        parent::__construct();
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
        // an arobase (@) is specified during that an HTML file is loading.
        @$this->dom->loadHTMLFile(__DIR__ . '/../templates/empty-doc.html');

        $this->renderGroupedClassesByNamespaces();
        $this->renderDocumentationFromMethods();
        $this->renderListOfProperties();
        $this->renderListOfMethods();

        $this->saveHTMLPage($outputPath);
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
     * Allows to display a list of namespaces. Each namespace contain the classes.
     *
     * @throws DOMException
     * @throws Exception
     */
    private function renderGroupedClassesByNamespaces(): void
    {
        $asideLeft = $this->dom->getElementById("asideNamespacesAndClasses");

        $this->createElement($asideLeft, TagType::H2_ELEMENT, "Classes");

        foreach (Classmap::getInstance()->getClassesGroupedByNamespaces() as $namespace => $classes) {
            $div = $this->createElement($asideLeft, TagType::DIV_ELEMENT);
            $this->createElement($div, TagType::H3_ELEMENT, $namespace);
            $ul = $this->createElement($div, TagType::UL_ELEMENT);

            foreach ($classes as $class) {
                $li = $this->createElement($ul, TagType::LI_ELEMENT);

                $this->createElement($li, TagType::A_ELEMENT, $class, [
                    'href' => $class . ".html"
                ]);
            }
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

            // Add a comma if it's not the last argument.
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
     * Give CSS properties depending on the scope and the deprecation.
     *
     * @param DocBlock $docBlock
     * @param ReflectionMethod $method
     * @return string A CSS property for the method.
     */
    private function giveCSSPropsToMethod(DocBlock $docBlock, ReflectionMethod $method): string
    {
        $cssProps = "border-bottom: 1px solid lightgrey; padding: 4px 24px 16px 24px;";

        if ($docBlock->hasTag("deprecated")) {
            $cssProps .= "background-color: yellow;";
        } else {
            if ($method->isPublic())
                $cssProps .= "background-color: #e9ffe6;";
            elseif ($method->isProtected())
                $cssProps .= "background-color: #fffde6;";
            elseif ($method->isPrivate())
                $cssProps .= "background-color: #ffe6e6;";
        }

        return $cssProps;
    }

    /**
     * Give the title of the method.
     *
     * @param ReflectionMethod $method
     * @return string
     */
    private function giveTitleOfMethod(ReflectionMethod $method): string
    {
        $methodTitle = "";

        if ($method->isPublic())
            $methodTitle = "&#x1F513;"; // open lock emoji.
        elseif ($method->isProtected())
            $methodTitle = "&#128273;"; // key emoji.
        elseif ($method->isPrivate())
            $methodTitle = "&#128274;"; // close lock emoji.

        $methodTitle .= " {$method->getName()}()";

        if (!empty($method->getReturnType()))
            $methodTitle .= ": " . $method->getReturnType();

        return $methodTitle;
    }

    /**
     * Display the deprecation message if the "@deprecated" annotation is indicated.
     *
     * @param DocBlock $docBlock
     * @param DOMElement $element
     * @return void
     * @throws DOMException
     */
    private function displayDeprecationMessage(DOMElement $element, DocBlock $docBlock): void
    {
        if ($docBlock->hasTag("deprecated")) {
            $description = $docBlock->getTagsByName("deprecated")[0]->getDescription();
            $deprecationMessage = "DEPRECATION WARNING : $description";

            $this->createElement($element, TagType::P_ELEMENT, $deprecationMessage, [
                'style' => "font-weight: bold;"
            ]);
        }
    }

    /**
     * Allows to display a documentation for each method.
     *
     * @throws DOMException
     */
    private function renderDocumentationFromMethods(): void
    {
        $main = $this->dom->getElementById("mainBlock");
        $docBlock = (new DocBlock($this->class));

        $this->createElement($main, TagType::H1_ELEMENT, $this->class->getShortName(), [
            "style" => "font-family: Ubuntu Sans Mono, monospace"
        ]);

        $this->createElement($main, TagType::P_ELEMENT, $docBlock->getShortDescription());

        foreach ($docBlock->getTags() as $tag) {
            $div = $this->createElement($main, TagType::DIV_ELEMENT, attributes: [
                'style' => 'padding: 8px 0'
            ]);

            $this->createElement($div, TagType::SPAN_ELEMENT, strtoupper($tag->getName()), [
                'style' => 'font-weight: bold; margin-right: 16px; padding: 6px 14px; background-color: grey; border-radius: 16px; color: white'
            ]);
            $this->createElement($div, TagType::SPAN_ELEMENT, $tag->getDescription());
        }

        $this->createElement($main, TagType::BR_ELEMENT);

        $this->renderMethodBlock($main);
    }

    /**
     * Render a list of properties in specified class.
     *
     * @throws DOMException
     */
    private function renderListOfProperties(): void
    {
        $asideRight = $this->dom->getElementById("asidePropertiesAndMethods");

        $this->createElement($asideRight, TagType::H2_ELEMENT, "Properties");
        $ul = $this->createElement($asideRight, TagType::UL_ELEMENT);

        foreach ($this->class->getProperties() as $property) {
            $li = $this->createElement($ul, TagType::LI_ELEMENT);
            $this->createElement($li, TagType::A_ELEMENT, $property->getName(), [
                "href" => "#doc2htmlProperty" . $property->getName()
            ]);
        }
    }

    /**
     * Render a list of methods in specified class.
     *
     * @throws DOMException
     */
    private function renderListOfMethods(): void
    {
        $asideRight = $this->dom->getElementById("asidePropertiesAndMethods");

        $this->createElement($asideRight, TagType::H2_ELEMENT, "Methods");
        $ul = $this->createElement($asideRight, TagType::UL_ELEMENT);

        foreach ($this->class->getMethods() as $method) {
            $li = $this->createElement($ul, TagType::LI_ELEMENT);
            $this->createElement($li, TagType::A_ELEMENT, $method->getName(), [
                "href" => "#doc2html_method_" . $method->getName()
            ]);
        }
    }

    /**
     * @throws DOMException
     */
    private function renderMethodBlock(DOMElement $element): void
    {
        foreach ($this->class->getMethods() as $method) {
            $docBlock = new DocBlock($method);

            $methodName = $method->getName();

            $div = $this->createElement($element, TagType::DIV_ELEMENT, attributes: [
                "id"    => "doc2html_method_" . $methodName,
                "style" => $this->giveCSSPropsToMethod($docBlock, $method)
            ]);

            $methodTitle = $this->giveTitleOfMethod($method);

            $this->createElement($div, TagType::H2_ELEMENT, $methodTitle, [
                "style" => "font-family: Ubuntu Sans Mono, monospace"
            ]);
            $this->createElement($div, TagType::PRE_ELEMENT, $this->buildSignature($method));
            $this->createElement($div, TagType::P_ELEMENT, $docBlock->getShortDescription());

            $this->renderTableOfArguments($div, $docBlock);
            $this->displayDeprecationMessage($div, $docBlock);
        }
    }

    /**
     * Render a table of arguments in the form of type, name and description.
     * @param DocBlock $docBlock
     * @param DOMElement $element
     * @return void
     * @throws DOMException
     */
    private function renderTableOfArguments(DOMElement $element, DocBlock $docBlock): void
    {
        if (!empty($docBlock->getTagsByName('param'))) {
            $this->createElement($element, TagType::H3_ELEMENT, "Arguments");

            $arguments = [];

            foreach ($docBlock->getTagsByName("param") as $argument) {
                $arguments[] = [
                    explode(" ", $argument->getContent())[0],
                    explode(" ", $argument->getContent())[1],
                    $argument->getDescription()
                ];
            }

            $this->createTable($element, $arguments, false, [
                'style' => 'width: 100%; font-family: Ubuntu Sans Mono, monospace;'
            ]);
        }
    }
}
