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
        self::$classmap->includeNamespaces("Examples\\Exclusions\\Inclusions");

        $this->assertSame(
            ["Examples\\Exclusions\\Inclusions"],
            self::$classmap->getIncludedNamespaces()
        );
    }

    #[Test]
    public function testExcludeClassInNamespaceExample(): void
    {
        self::$classmap->excludeClasses("Examples\\Exclusions\\Inclusions\\PersonalExample");

        $this->assertTrue(
            in_array("Examples\\Exclusions\\Inclusions\\PersonalExample", self::$classmap->getExcludedClasses())
        );
    }

    #[Test]
    public function testIncludeClassInNamespaceExample(): void
    {
        self::$classmap->includeClasses("Examples\\Exclusions\\PhoneExample");

        $this->assertTrue(
            in_array("Examples\\Exclusions\\PhoneExample", self::$classmap->getIncludedClasses())
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
            "Examples\\Annotations" => [
                "BookExample"
            ],
            "Examples\\Exclusions" => [
                "BookExample", "CarExample", "PersonExample", "PhoneExample"
            ],
            'Examples\\Exclusions\\Inclusions' => [
                "ExceptedExample", "PersonalExample"
            ]
        ];

        $this->assertSame($expected, $classmap);
    }
}
