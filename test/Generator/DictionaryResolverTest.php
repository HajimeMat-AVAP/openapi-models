<?php

declare(strict_types=1);

namespace Reinfi\OpenApiModels\Test\Generator;

use Nette\PhpGenerator\ClassLike;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpNamespace;
use PHPUnit\Framework\TestCase;
use Reinfi\OpenApiModels\Generator\DictionaryResolver;

class DictionaryResolverTest extends TestCase
{
    public function testItResolvesDictionaryConstructor(): void
    {
        $resolver = new DictionaryResolver();

        $namespace = new PhpNamespace('Api');
        $class = $namespace->addClass('Test');
        $class->addMethod('__construct');

        $resolver->resolve($namespace, 'Test', $class, 'string');

        $constructor = $class->getMethod('__construct');

        self::assertTrue($constructor->isVariadic());
        self::assertTrue($constructor->hasParameter('dictionaries'));
        self::assertStringContainsString('$this->dictionaries = $dictionaries;', $constructor->getBody());

        $parameter = $constructor->getParameter('dictionaries');
        self::assertEquals('Api\TestDictionary', $parameter->getType());
    }

    public function testItResolvesDictionaryProperty(): void
    {
        $resolver = new DictionaryResolver();

        $namespace = new PhpNamespace('Api');
        $class = $namespace->addClass('Test');
        $class->addMethod('__construct');

        $resolver->resolve($namespace, 'Test', $class, 'string');

        self::assertTrue($class->hasProperty('dictionaries'));

        $property = $class->getProperty('dictionaries');

        self::assertEquals(ClassLike::VisibilityPrivate, $property->getVisibility());
        self::assertEquals('array', $property->getType());
        self::assertNotNull($property->getComment());
        self::assertStringContainsString('@var Api\TestDictionary[]', $property->getComment());
    }

    public function testItResolvesDictionaryClass(): void
    {
        $resolver = new DictionaryResolver();

        $namespace = new PhpNamespace('Api');
        $class = $namespace->addClass('Test');
        $class->addMethod('__construct');

        $resolver->resolve($namespace, 'Test', $class, 'string');

        $classes = $namespace->getClasses();

        self::assertArrayHasKey('TestDictionary', $classes);

        $dictionaryClass = $classes['TestDictionary'];

        self::assertInstanceOf(ClassType::class, $dictionaryClass);
        self::assertTrue($dictionaryClass->isReadOnly());
        self::assertEquals('TestDictionary', $dictionaryClass->getName());

        self::assertTrue($dictionaryClass->hasMethod('__construct'));

        $constructor = $dictionaryClass->getMethod('__construct');

        self::assertTrue($constructor->hasParameter('key'));
        self::assertTrue($constructor->hasParameter('value'));

        self::assertEquals('string', $constructor->getParameter('key')->getType());
        self::assertEquals('string', $constructor->getParameter('value')->getType());
    }
}
