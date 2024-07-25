<?php

use Tamdaz\Doc2Html\Classmap;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;

class ClassmapTest extends TestCase
{
    /**
     * @var Classmap|null
     */
    protected static ?Classmap $classmap = null;

    public static function setUpBeforeClass(): void
    {
        self::$classmap = Classmap::getInstance();
        self::$classmap->excludeNamespace("Tamdaz");
    }

    #[Test]
    public function testExcludeNamespaceExample(): void
    {
        self::$classmap->excludeNamespace("Examples\\Exclusions");

        $this->assertTrue(
            in_array("Examples\\Exclusions", self::$classmap->getExcludedNamespaces())
        );
    }

    #[Test]
    public function testIncludeNamespaceExample(): void
    {
        self::$classmap->includeNamespaces("Examples\\Exclusions\\Group");

        $this->assertSame(
            ["Examples\\Exclusions\\Group"],
            self::$classmap->getIncludedNamespaces()
        );
    }

    #[Test]
    public function testExcludeClassInNamespaceExample(): void
    {
        self::$classmap->excludeClasses("Examples\\DeprecationExample");

        $this->assertTrue(
            in_array("Examples\\DeprecationExample", self::$classmap->getExcludedClasses())
        );
    }

    #[Test]
    public function testIncludeClassInNamespaceExample(): void
    {
        self::$classmap->includeClasses("Examples\\Exclusions\\ExceptedClassExample");

        $this->assertTrue(
            in_array("Examples\\Exclusions\\ExceptedClassExample", self::$classmap->getIncludedClasses())
        );
    }

    /**
     * @throws ReflectionException
     */
    #[Test]
    public function testExpectedExcludedNamespaces(): void
    {
        self::$classmap->generate();

        $classmap = self::$classmap->getClassesGroupedByNamespaces();
        $expected = [
            "Examples" => [
                // Sort A-Z
                "DeprecationExample", "MagicMethodsExample", "MathExample", "PersonExample"
            ],
            "Examples\\Exclusions" => [
                // Sort A-Z
                "ExceptedClassExample", "ExcludedClassExample",
            ],
            'Examples\Exclusions\Group' => [
                'ExceptedGroupClassExample'
            ]
        ];

        $this->assertSame($expected, $classmap);
    }
}
