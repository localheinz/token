<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @link https://github.com/localheinz/token
 */

namespace Localheinz\Token\Test\Unit\Exception;

use Localheinz\Test\Util\Helper;
use Localheinz\Token\Exception\IndexOutOfBounds;
use PHPUnit\Framework;

final class IndexOutOfBoundsTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsOutOfBoundsException()
    {
        $this->assertClassExtends(\OutOfBoundsException::class, IndexOutOfBounds::class);
    }

    public function testFromCountAndIndexReturnsException()
    {
        $faker = $this->faker();

        $count = $faker->randomNumber();
        $index = $faker->randomNumber();

        $exception = IndexOutOfBounds::fromCountAndIndex(
            $count,
            $index
        );

        $this->assertInstanceOf(IndexOutOfBounds::class, $exception);

        $message = \sprintf(
            'Index needs to be equal to or greater than "%d" and less than "%d", but "%d" is not.',
            0,
            $count,
            $index
        );

        $this->assertSame($message, $exception->getMessage());
        $this->assertSame(0, $exception->getCode());
        $this->assertSame($count, $exception->count());
        $this->assertSame($index, $exception->index());
    }
}
