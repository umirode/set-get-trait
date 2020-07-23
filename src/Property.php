<?php

declare(strict_types=1);

namespace Umirode\SetGetTrait;

use Doctrine\Common\Annotations\Annotation\Required;
use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Property
 * @package Umirode\SetGetTrait
 *
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class Property
{
    /**
     * @var bool
     */
    public $get = false;

    /**
     * @var bool
     */
    public $set = false;
}
