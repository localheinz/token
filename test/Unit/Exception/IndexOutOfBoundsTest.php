<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/token
 */

namespace Localheinz\Token\Test\Unit\Exception;

use Localheinz\Test\Util\Helper;
use Localheinz\Token\Exception\IndexOutOfBounds;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Localheinz\Token\Exception\IndexOutOfBounds
 */
final class IndexOutOfBoundsTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsOutOfBoundsException(): void
    {
        $this->assertClassExtends(\OutOfBoundsException::class, IndexOutOfBounds::class);
    }

    public function testFromCountAndIndexReturnsException(): void
    {
        $faker = $this->faker();

        $count = $faker->randomNumber();
        $index = $faker->randomNumber();

        $exception = IndexOutOfBounds::fromCountAndIndex(
            $count,
            $index
        );

        self::assertInstanceOf(IndexOutOfBounds::class, $exception);

        $message = \sprintf(
            'Index needs to be equal to or greater than "%d" and less than "%d", but "%d" is not.',
            0,
            $count,
            $index
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($count, $exception->count());
        self::assertSame($index, $exception->index());
    }
}
