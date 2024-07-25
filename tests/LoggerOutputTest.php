<?php

use PHPUnit\Framework\TestCase;
use Tamdaz\Doc2Html\LoggerOutput;
use PHPUnit\Framework\Attributes\Test;

class LoggerOutputTest extends TestCase
{
    #[Test]
    public function testDisplayInfo(): void
    {
        $output = $this->getBuffer(
            fn () => LoggerOutput::info("This is an information indicated in blue.")
        );

        $expectedType    = "[\e[34mINFO\e[39m]";
        $expectedMessage = "This is an information indicated in blue.";

        $this->assertTrue(str_contains($output, $expectedType));
        $this->assertTrue(str_contains($output, $expectedMessage));
        $this->assertSame(implode(' ', [$expectedType, $expectedMessage]), $output);
    }

    #[Test]
    public function testDisplaySuccess(): void
    {
        $output = $this->getBuffer(
            fn () => LoggerOutput::success("This is a success indicated in green.")
        );

        $expectedType    = "[ \e[32mOK\e[39m ]";
        $expectedMessage = "This is a success indicated in green.";

        $this->assertTrue(str_contains($output, $expectedType));
        $this->assertTrue(str_contains($output, $expectedMessage));
        $this->assertSame(implode(' ', [$expectedType, $expectedMessage]), $output);
    }

    #[Test]
    public function testDisplayWarning(): void
    {
        $output = $this->getBuffer(
            fn () => LoggerOutput::warning("This is a warning indicated in yellow.")
        );

        $expectedType    = "[\e[33mWARN\e[39m]";
        $expectedMessage = "This is a warning indicated in yellow.";

        $this->assertTrue(str_contains($output, $expectedType));
        $this->assertTrue(str_contains($output, $expectedMessage));
        $this->assertSame(implode(' ', [$expectedType, $expectedMessage]), $output);
    }

    #[Test]
    public function testDisplayError(): void
    {
        $output = $this->getBuffer(
            fn () => LoggerOutput::error("This is an error indicated in red.")
        );

        $expectedType    = "[ \e[31m!!\e[39m ]";
        $expectedMessage = "This is an error indicated in red.";

        $this->assertTrue(str_contains($output, $expectedType));
        $this->assertTrue(str_contains($output, $expectedMessage));
        $this->assertSame(implode(' ', [$expectedType, $expectedMessage]), $output);
    }

    /**
     * Put the output in a buffer.
     *
     * @param callable $output
     * @return false|string
     */
    private function getBuffer(callable $output): false|string
    {
        ob_start();
        $output();
        return ob_get_clean();
    }
}