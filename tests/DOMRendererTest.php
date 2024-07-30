<?php

use Tamdaz\Doc2Html\Config;
use PHPUnit\Framework\TestCase;
use Tamdaz\Doc2Html\DOMRenderer;
use Tamdaz\Doc2Html\Enums\TagType;
use PHPUnit\Framework\Attributes\Test;

class DOMRendererTest extends TestCase
{
    /**
     * @var DOMRenderer
     */
    protected static DOMRenderer $DOMRenderer;

    public static function setUpBeforeClass(): void
    {
        self::$DOMRenderer = new DOMRenderer();

        Config::$envMode = "dev";
    }

    /**
     * @return array
     */
    private function initializeClass(): array
    {
        $class = new ReflectionClass(DOMRenderer::class);

        $prop = $class->getProperty('dom');
        $prop->setAccessible(true);

        /** @var DOMDocument $dom */
        $dom = $prop->getValue(self::$DOMRenderer);
        $dom->loadHTML("<div id='root'></div>");
        $root = $dom->getElementById('root');

        $createElementMethod = $class->getMethod('createElement');
        $createElementMethod->setAccessible(true);

        $saveHTMLPageMethod = $class->getMethod('saveHTMLPage');
        $saveHTMLPageMethod->setAccessible(true);

        return [$root, $createElementMethod, $saveHTMLPageMethod];
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testCreateElement(): void
    {
        [$root, $createElementMethod] = $this->initializeClass();

        /** @var DOMElement $newElement */
        $newElement = $createElementMethod->invoke(self::$DOMRenderer, $root, TagType::H1_ELEMENT, "Hello world");

        $this->assertInstanceOf(DOMElement::class, $newElement);
        $this->assertSame("Hello world", $newElement->nodeValue);
        $this->assertSame("h1", $newElement->tagName);
        $this->assertTrue($newElement->parentElement->tagName === "div");
        $this->assertTrue($newElement->parentElement->id === "root");
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testCreateElementWithAttributes(): void
    {
        [$root, $createElementMethod] = $this->initializeClass();

        /** @var DOMElement $newElement */
        $newElement = $createElementMethod->invoke(
            self::$DOMRenderer, $root, TagType::P_ELEMENT, "Lorem ipsum dolor sit amet.", [
                'id' => 'myNewId',
                'class' => 'myNewClass'
            ]
        );

        $this->assertInstanceOf(DOMElement::class, $newElement);
        $this->assertSame("p", $newElement->tagName);
        $this->assertSame("Lorem ipsum dolor sit amet.", $newElement->nodeValue);

        $this->assertTrue($newElement->hasAttribute('id'));
        $this->assertSame("myNewId", $newElement->getAttribute('id'));

        $this->assertTrue($newElement->hasAttribute('class'));
        $this->assertSame("myNewClass", $newElement->getAttribute('class'));

        $this->assertTrue($newElement->parentElement->tagName === "div");
        $this->assertTrue($newElement->parentElement->id === "root");
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testRenderDomInHtml(): void
    {
        [$root, $createElementMethod, $saveHTMLPageMethod] = $this->initializeClass();

        /** @var DOMElement $newElement */
        $createElementMethod->invoke(self::$DOMRenderer, $root, TagType::P_ELEMENT, "Lorem ipsum dolor sit amet.", [
            'id' => 'myNewId',
            'class' => 'myNewClass'
        ]);

        $saveHTMLPageMethod->invoke(self::$DOMRenderer, '/tmp/test.html');

        $result = str_contains(
            file_get_contents('/tmp/test.html'),
            '<div id="root"><p id="myNewId" class="myNewClass">Lorem ipsum dolor sit amet.</p></div>'
        );

        $this->assertTrue($result);

        unlink('/tmp/test.html');
    }
}
