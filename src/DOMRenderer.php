<?php

namespace Tamdaz\Doc2Html;

use DOMDocument;
use DOMElement;
use DOMException;
use Tamdaz\Doc2Html\Enums\TagType;

class DOMRenderer
{
    /**
     * @var DOMDocument
     */
    protected DOMDocument $dom;

    public function __construct()
    {
        $this->dom = new DOMDocument('1.0');
    }

    /**
     * Add a new element and add it to parent element.
     *
     * @throws DOMException
     */
    protected function createElement(
        DOMElement $parentElement, TagType|string $tag, mixed $value = null, ?array $attributes = null
    ): DOMElement
    {
        $newElement = $this->dom->createElement($tag->value, $value);

        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $newElement->setAttribute($key, $value);
            }
        }

        $parentElement->appendChild($newElement);

        return $newElement;
    }

    /**
     * Render HTML page and save it as a file.
     *
     * @param string $path
     * @return bool
     */
    protected function saveHTMLPage(string $path): bool
    {
        return $this->dom->saveHTMLFile($path);
    }
}