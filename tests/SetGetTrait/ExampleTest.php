<?php

declare(strict_types=1);

namespace Umirode\SetGetTrait\Tests;

use PHPUnit\Framework\TestCase;

/**
 * Class ExampleTest
 * @package Umirode\SetGetTrait\Tests
 */
final class ExampleTest extends TestCase
{
    public function testUserName(): void
    {
        $example = new Example();

        $example->setUsername('test');

        self::assertEquals('test', $example->getUsername());
    }

    public function testUserNameInvalidType(): void
    {
        $this->expectExceptionMessage('Type error, value of type "integer" is not valid for property "username"');
        $example = new Example();

        $example->setUsername(123);
    }

    public function testUserNameGetNullValue(): void
    {
        $this->expectExceptionMessage('Type error, value of type "NULL" is not valid for method "setUsername"');
        $example = new Example();

        $example->getUsername();
    }
}
