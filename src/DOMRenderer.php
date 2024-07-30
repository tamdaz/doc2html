<?php

namespace Tamdaz\Doc2Html;

use DOMElement;
use DOMDocument;
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
     * @param DOMElement $parentElement
     * @param TagType|string $tag
     * @param mixed|null $value
     * @param array<string, mixed>|null $attributes
     * @return DOMElement
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
     * @param DOMElement $parentElement
     * @param array<int, array<int, mixed>> $matrix
     * @param array<string, string>|null $attributes
     * @param bool $enableHeaders
     * @return DOMElement
     * @throws DOMException
     */
    protected function createTable(
        DOMElement $parentElement, array $matrix, bool $enableHeaders = true, ?array $attributes = null
    ): DOMElement
    {
        $tableElement = $this->createElement($parentElement, TagType::TABLE_ELEMENT, attributes: $attributes);

        $isFirstRow = $enableHeaders;

        $tHeadElement = $this->createElement($tableElement, TagType::THEAD_ELEMENT);
        $TrElementForHead = $this->createElement($tHeadElement, TagType::TR_ELEMENT);

        $tBodyElement = $this->createElement($tableElement, TagType::TBODY_ELEMENT);

        foreach ($matrix as $row) {
            $TrElementForBody = $this->createElement($tBodyElement, TagType::TR_ELEMENT);

            foreach ($row as $cell) {
                $trForHeadOrBody = ($isFirstRow === true) ? $TrElementForHead : $TrElementForBody;
                $thOrTdElement = ($isFirstRow === true) ? TagType::TH_ELEMENT : TagType::TD_ELEMENT;

                $this->createElement($trForHeadOrBody, $thOrTdElement, $cell);
            }

            $isFirstRow = false;
        }

        return $tableElement;
    }

    /**
     * Render HTML page and save it as a file.
     *
     * @param string $path
     * @return bool
     */
    protected function saveHTMLPage(string $path): bool
    {
        // Create the folder (not the file) if it does not exist.
        if (!file_exists(Config::getOutputDir()))
            mkdir(Config::getOutputDir(), 0755); // default directory permission.

        return $this->dom->saveHTMLFile($path);
    }
}