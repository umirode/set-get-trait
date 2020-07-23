<?php

declare(strict_types=1);

namespace Umirode\SetGetTrait\Tests;

use Umirode\SetGetTrait\Property;
use Umirode\SetGetTrait\SetGetTrait;

/**
 * Class Example
 * @package Umirode\SetGetTrait\Tests
 *
 * @method string getUsername()
 * @method string setUsername(string $username)
 * @method string setName(string $name)
 * @method string getSurname()
 *
 * Methods for tests
 * @method testMethod()
 * @method getName()
 * @method setEmptyDoc(string $string)
 * @method setSurname(string $string)
 * @method setSetSet(string $string)
 * @method getEmptyDoc(string $string)
 */
final class Example
{
    use SetGetTrait;

    /**
     * @var string
     * @Property(get=true, set=true)
     */
    private $username;

    /**
     * @var string
     * @Property(set=true)
     */
    private $name;

    /**
     * @var string|null
     * @Property(get=true)
     */
    private $surname;

    private $emptyDoc;

    /**
     * @var string
     * @Property(get=true, set=true)
     */
    private $setSetTest;
}
