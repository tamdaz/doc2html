<?php

namespace Tamdaz\Doc2Html;

use Exception;
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

        $this->renderListOfNamespaces();
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
    private function renderListOfNamespaces(): void
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
     * Allows to display a documentation for each method.
     *
     * @throws DOMException
     */
    private function renderDocumentationFromMethods(): void
    {
        $main = $this->dom->getElementById("mainBlock");

        $this->createElement($main, TagType::H1_ELEMENT, $this->class->getShortName(), [
            "style" => "font-family: Ubuntu Sans Mono, monospace"
        ]);

        $description = (new DocBlock($this->class))->getShortDescription();
        $this->createElement($main, TagType::P_ELEMENT, $description);

        foreach ($this->class->getMethods() as $method) {
            $docBlock = new DocBlock($method);

            [$methodName, $returnType] = [
                $method->getName(),
                $method->getReturnType()
            ];

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

            $div = $this->createElement($main, TagType::DIV_ELEMENT, attributes: [
                "id"    => "doc2html_method_" . $methodName,
                "style" => $cssProps
            ]);

            $methodTitle = "";

            if ($method->isPublic())
                $methodTitle = "&#x1F513;"; // cadenas ouvert
            elseif ($method->isProtected())
                $methodTitle = "&#128273;"; // key emoji
            elseif ($method->isPrivate())
                $methodTitle = "&#128274;"; // cadenas fermé

            $methodTitle .= " $methodName()";

            if (!empty($returnType))
                $methodTitle .= ": $returnType";

            $this->createElement($div, TagType::H2_ELEMENT, $methodTitle, [
                "style" => "font-family: Ubuntu Sans Mono, monospace"
            ]);

            $this->createElement($div, TagType::PRE_ELEMENT, $this->buildSignature($method));

            $this->createElement($div, TagType::P_ELEMENT, $docBlock->getShortDescription());

            if (!empty($docBlock->getTagsByName('param'))) {
                $this->createElement($div, TagType::H3_ELEMENT, "Arguments");

                foreach ($docBlock->getTagsByName("param") as $param) {
                    // Put the arobase to ignore warnings.
                    @[$paramType, $paramName, $paramDescription] = @explode(" ", $param->getContent(), 3);

                    $infoParam = "$paramType, $paramName : $paramDescription";
                    $this->createElement($div, TagType::P_ELEMENT, $infoParam);
                }
            }

            if ($docBlock->hasTag("deprecated")) {
                $deprecationMessage = "DEPRECATION WARNING : " .
                    $docBlock
                        ->getTagsByName("deprecated")[0]
                        ->getDescription()
                ;

                $this->createElement($div, TagType::P_ELEMENT, $deprecationMessage, [
                    'style' => "font-weight: bold;"
                ]);
            }
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
}
