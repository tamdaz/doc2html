<?php

use Tamdaz\Doc2Html\Config;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ConfigTest extends TestCase
{
    public static function setUpBeforeClass(): void
    {
        Config::$envMode = "test";
    }

    #[Test]
    public function testGetConfigFile(): void
    {
        $config = Config::getConfig();

        $this->assertIsObject($config);
        $this->assertObjectHasProperty("output_dir", $config);
        $this->assertObjectHasProperty("verbose", $config);
        $this->assertObjectHasProperty("exclude_namespaces", $config);
        $this->assertObjectHasProperty("include_namespaces", $config);
        $this->assertObjectHasProperty("exclude_classes", $config);
        $this->assertObjectHasProperty("include_classes", $config);
    }

    #[Test]
    public function testGetOutputDir(): void
    {
        $this->assertIsString(Config::getOutputDir());
    }

    #[Test]
    public function testGetVerbose(): void
    {
        $isVerbose = Config::isVerbose();

        $this->assertIsBool($isVerbose);
        $this->assertTrue(true, $isVerbose);
    }

    #[Test]
    public function testGetNamespacesToExclude(): void
    {
        $namespacesToExclude = Config::excludeNamespaces();

        $this->assertIsArray($namespacesToExclude);
        $this->assertNotNull($namespacesToExclude);

        $this->assertSame(
            ["Namespace\\Exclude"], $namespacesToExclude
        );
    }

    #[Test]
    public function testGetNamespacesToInclude(): void
    {
        $namespacesToInclude = Config::includeNamespaces();

        $this->assertIsArray($namespacesToInclude);
        $this->assertNotNull($namespacesToInclude);

        $this->assertSame(
            ["Namespace\\Exclude\\Include"], $namespacesToInclude
        );
    }

    #[Test]
    public function testGetClassesToExclude(): void
    {
        $classesToExclude = Config::excludeClasses();

        $this->assertIsArray($classesToExclude);
        $this->assertNotNull($classesToExclude);

        $this->assertSame(
            ["Namespace\\Exclude\\Include\\PersonalClass"], $classesToExclude
        );
    }

    #[Test]
    public function testGetClassesToInclude(): void
    {
        $classesToInclude = Config::includeClasses();

        $this->assertIsArray($classesToInclude);
        $this->assertNotNull($classesToInclude);

        $this->assertSame(
            ["Namespace\\Exclude\\Include\\NonPersonalClass"], $classesToInclude
        );
    }
}