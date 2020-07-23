Magic trait for getters and setters
================================
[![Latest Stable Version](https://poser.pugx.org/umirode/set-get-trait/version)](https://packagist.org/packages/umirode/set-get-trait)
[![Build Status](https://travis-ci.org/umirode/set-get-trait.svg?branch=master)](https://travis-ci.org/umirode/set-get-trait)
[![Codecov](https://codecov.io/gh/umirode/set-get-trait/branch/master/graph/badge.svg)](https://codecov.io/gh/umirode/set-get-trait/)


## Installation

`composer require umirode/set-get-trait`

## Example

```Php
<?php

namespace Entity\Product;

use Umirode\SetGetTrait\Property;
use Umirode\SetGetTrait\SetGetTrait;

/**
 * Class Product
 * @package Entity\Product
 *
 * @method int getId();
 *
 * @method float getPrice();
 * @method void setPrice(float $price);
 *
 * @method string getTitle();
 * @method void setTitle(string $title);
 *
 * @method string getBrand();
 * @method void setBrand(string $brand);
 *
 * @method string getArticle();
 * @method void setArticle(float $article);
 */
final class Product
{
    use SetGetTrait;

    /**
     * @var int
     *
     * @Property(get=true)
     */
    private $id;

    /**
     * @var float
     *
     * @Property(set=true, get=true)
     */
    private $price;

    /**
     * @var string
     *
     * @Property(set=true, get=true)
     */
    private $title;

    /**
     * @var string
     *
     * @Property(set=true, get=true)
     */
    private $brand;

    /**
     * @var string
     *
     * @Property(set=true, get=true)
     */
    private $article;
}
```
