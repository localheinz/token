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
use Localheinz\Token\Exception\NoSignificantTokenFound;
use Localheinz\Token\Sequence;
use PHPUnit\Framework;

final class NoSignificantTokenFoundTest extends Framework\TestCase
{
    use Helper;

    public function testExtendsRuntimeException()
    {
        $this->assertClassExtends(\RuntimeException::class, NoSignificantTokenFound::class);
    }

    /**
     * @dataProvider providerDirectionAndFormat
     *
     * @param int    $direction
     * @param string $format
     */
    public function testInReturnsException(int $direction, string $format)
    {
        $index = $this->faker()->randomNumber();

        $exception = NoSignificantTokenFound::in(
            $direction,
            $index
        );

        $this->assertInstanceOf(NoSignificantTokenFound::class, $exception);

        $message = \sprintf(
            $format,
            $index
        );

        $this->assertSame(0, $exception->getCode());
        $this->assertSame($message, $exception->getMessage());
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

        foreach ($values as $key => list($direction, $format)) {
            yield $key => [
                $direction,
                $format,
            ];
        }
    }
}
