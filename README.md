# token

[![Build Status](https://travis-ci.org/localheinz/token.svg?branch=master)](https://travis-ci.org/localheinz/token)
[![Code Climate](https://codeclimate.com/github/localheinz/token/badges/gpa.svg)](https://codeclimate.com/github/localheinz/token)
[![Test Coverage](https://codeclimate.com/github/localheinz/token/badges/coverage.svg)](https://codeclimate.com/github/localheinz/token/coverage)
[![Issue Count](https://codeclimate.com/github/localheinz/token/badges/issue_count.svg)](https://codeclimate.com/github/localheinz/token)
[![Latest Stable Version](https://poser.pugx.org/localheinz/token/v/stable)](https://packagist.org/packages/localheinz/token)
[![Total Downloads](https://poser.pugx.org/localheinz/token/downloads)](https://packagist.org/packages/localheinz/token)

Provides a simple read-only abstraction for a token and a sequence of tokens, inspired by [`friendsofphp/php-cs-fixer`](http://github.com/FriendsOfPHP/PHP-CS-Fixer).

## Installation

Run

```
$ composer require localheinz/token
```

## Usage

### Sequence of tokens

Create a sequence of tokens from source code:

```php
use Localheinz\Token\Sequence;

$source = <<<'PHP'
<?php

class Foo
{
    /**
     * @param Bar $bar
     */
    public function __construct(Bar $bar)
    {
        $this->bar = $bar;
    }
}
PHP;

$sequence = Sequence::fromSource($source);
```

### Token in sequence

Retrieve the token at an index in the sequence of tokens:

```php
use Localheinz\Token\Token;

/** @var Token $token */
$token = $sequence->at(10);

var_dump($token->index()); // 10
var_dump($token->isType(T_PUBLIC)); // true
var_dump($token->isContent('public')); // true
```

## Contributing

Please have a look at [`CONTRIBUTING.md`](.github/CONTRIBUTING.md).

## Code of Conduct

Please have a look at [`CODE_OF_CONDUCT.md`](.github/CODE_OF_CONDUCT.md).

## License

This package is licensed using the MIT License.
