<?php

use PHPUnit\Framework\TestCase;
use Tamdaz\Doc2Html\CommandRegister;
use PHPUnit\Framework\Attributes\Test;
use Tamdaz\Doc2Html\Config;

class CommandRegisterTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        Config::$envMode = "test";
    }

    #[Test]
    public function testExpectArguments(): void
    {
        $args = ["hello", "world", "--this", "--is", "-a", "-n", "--option"];

        CommandRegister::getInstance($args);

        $result = CommandRegister::getInstance()->getArguments();

        $this->assertContains("hello", $result);
        $this->assertContains("world", $result);
    }

    #[Test]
    public function testExpectOptions(): void
    {
        $args = ["hello", "world", "--this", "--is", "-a", "-n", "--option"];

        CommandRegister::getInstance($args);

        $result = CommandRegister::getInstance()->getOptions();

        $this->assertArrayHasKey("--this", $result);
        $this->assertArrayHasKey("--is", $result);
        $this->assertArrayHasKey("-a", $result);
        $this->assertArrayHasKey("-n", $result);
        $this->assertArrayHasKey("--option", $result);
    }
}
