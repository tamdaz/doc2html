<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use Tamdaz\Doc2Html\DOMRenderer;
use Tamdaz\Doc2Html\Enums\TagType;

class DOMRendererTest extends TestCase
{
    /**
     * @throws ReflectionException
     * @throws DOMException
     */
    #[Test]
    public function testCreateElement(): void
    {
        [$method, $DOMRenderer, $parentElement] = $this->stepCreateDOM();

        $newElement = $method->invoke($DOMRenderer, $parentElement, TagType::H1_ELEMENT, 'Hello world');

        $this->assertInstanceOf(DOMElement::class, $newElement);
        $this->assertSame("Hello world", $newElement->nodeValue);
        $this->assertSame("h1", $newElement->tagName);
        $this->assertSame("root", $newElement->parentElement->tagName);
    }

    /**
     * @throws ReflectionException
     * @throws DOMException
     */
    #[Test]
    public function testCreateElementWithAnAttribute(): void
    {
        [$method, $DOMRenderer, $parentElement] = $this->stepCreateDOM();

        $newElement = $method->invoke($DOMRenderer, $parentElement, TagType::H1_ELEMENT, 'Hello world', [
            'id' => 'myTitle'
        ]);

        $this->assertInstanceOf(DOMElement::class, $newElement);
        $this->assertSame("Hello world", $newElement->nodeValue);
        $this->assertSame("h1", $newElement->tagName);
        $this->assertSame("root", $newElement->parentElement->tagName);

        $this->assertTrue($newElement->hasAttribute('id'));
        $this->assertSame("myTitle", $newElement->getAttribute('id'));
    }

    /**
     * @throws ReflectionException
     * @throws DOMException
     */
    #[Test]
    public function testCreateElementWithAttributes(): void
    {
        [$method, $DOMRenderer, $parentElement] = $this->stepCreateDOM();

        $newElement = $method->invoke($DOMRenderer, $parentElement, TagType::H1_ELEMENT, 'Hello world', [
            'id' => 'myTitle',
            'class' => 'myCssClass'
        ]);

        $this->assertInstanceOf(DOMElement::class, $newElement);
        $this->assertSame("Hello world", $newElement->nodeValue);
        $this->assertSame("h1", $newElement->tagName);
        $this->assertSame("root", $newElement->parentElement->tagName);

        $this->assertTrue($newElement->hasAttribute('id'));
        $this->assertSame("myTitle", $newElement->getAttribute('id'));

        $this->assertTrue($newElement->hasAttribute('class'));
        $this->assertSame("myCssClass", $newElement->getAttribute('class'));
    }

    /**
     * @return array
     */
    private function stepCreateDOM(): array
    {
        $DOMRenderer = new DOMRenderer();

        $reflection = new ReflectionClass($DOMRenderer);

        // Get DOMRenderer::$dom property.
        $property = $reflection->getProperty('dom');
        $property->setAccessible(true);

        $dom = $property->getValue($DOMRenderer);
        $parentElement = $dom->createElement('root');

        $method = $reflection->getMethod("createElement");
        $method->setAccessible(true);

        return [$method, $DOMRenderer, $parentElement];
    }
}
