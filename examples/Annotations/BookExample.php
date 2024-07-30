<?php

namespace Examples\Annotations;

/**
 * An example class about the book.
 *
 * @author John Doe <john.doe@gmail.com>
 * @todo This is a todo message.
 * @info This is an information message.
 */
class BookExample
{
    /**
     * @var string Title of a book.
     */
    private string $title;

    /**
     * @var string Author of a book.
     */
    private string $author;

    /**
     * @var int A number of pages in a book.
     */
    private int $numberOfPages;

    /**
     * Get the title of the book.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Change the title of the book.
     *
     * @param string $title New title.
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get the author of the book.
     *
     * @return string
     */
    public function getAuthor(): string
    {
        return $this->author;
    }

    /**
     * Change the author of the book.
     *
     * @param string $author New author.
     */
    public function setAuthor(string $author): void
    {
        $this->author = $author;
    }

    /**
     * Get the number of pages in the book.
     *
     * @return int
     */
    public function getNumberOfPages(): int
    {
        return $this->numberOfPages;
    }

    /**
     * Change the number of pages in the book.
     *
     * @param int $numberOfPages New number of pages.
     */
    public function setNumberOfPages(int $numberOfPages): void
    {
        $this->numberOfPages = $numberOfPages;
    }
}