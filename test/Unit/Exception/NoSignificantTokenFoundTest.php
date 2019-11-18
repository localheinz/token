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
use Localheinz\Token\Exception\NoSignificantTokenFound;
use Localheinz\Token\Sequence;
use PHPUnit\Framework;

/**
 * @internal
 *
 * @covers \Localheinz\Token\Exception\NoSignificantTokenFound
 */
final class NoSignificantTokenFoundTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsRuntimeException(): void
    {
        self::assertClassExtends(\RuntimeException::class, NoSignificantTokenFound::class);
    }

    /**
     * @dataProvider providerDirectionAndFormat
     *
     * @param int    $direction
     * @param string $format
     */
    public function testInReturnsException(int $direction, string $format): void
    {
        $index = self::faker()->randomNumber();

        $exception = NoSignificantTokenFound::in(
            $direction,
            $index
        );

        self::assertInstanceOf(NoSignificantTokenFound::class, $exception);

        $message = \sprintf(
            $format,
            $index
        );

        self::assertSame($message, $exception->getMessage());
        self::assertSame(0, $exception->getCode());
        self::assertSame($direction, $exception->direction());
        self::assertSame($index, $exception->index());
    }

    public function providerDirectionAndFormat(): \Generator
    {
        $values = [
            'before' => [
                Sequence::DIRECTION_BACKWARD,
                'Could not find a significant token before index "%d".',
            ],
            'after' => [
                Sequence::DIRECTION_FORWARD,
                'Could not find a significant token after index "%d".',
            ],
        ];

        foreach ($values as $key => [$direction, $format]) {
            yield $key => [
                $direction,
                $format,
            ];
        }
    }
}
