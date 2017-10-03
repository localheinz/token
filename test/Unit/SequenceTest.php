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

namespace Localheinz\Token\Test\Unit;

use Localheinz\Test\Util\Helper;
use Localheinz\Token\Exception;
use Localheinz\Token\Sequence;
use Localheinz\Token\Token;
use PHPUnit\Framework;

final class SequenceTest extends Framework\TestCase
{
    use Helper;

    public function testImplementsCountableInterface()
    {
        $this->assertClassImplementsInterface(\Countable::class, Sequence::class);
    }

    public function testConstants()
    {
        $this->assertSame(-1, Sequence::DIRECTION_BACKWARD);
        $this->assertSame(1, Sequence::DIRECTION_FORWARD);
    }

    public function testFromSourceReturnsSequenceOfTokens()
    {
        $source = \file_get_contents(__FILE__);

        $sequence = Sequence::fromSource($source);

        $this->assertInstanceOf(Sequence::class, $sequence);
    }

    public function testCountReturnsNumberOfTokens()
    {
        $source = \file_get_contents(__FILE__);
        $tokens = \token_get_all(
            $source,
            TOKEN_PARSE
        );
        $count = \count($tokens);

        $sequence = Sequence::fromSource($source);

        $this->assertCount($count, $sequence);
    }

    /**
     * @dataProvider providerIndexOutOfBounds
     *
     * @param string $source
     * @param int    $index
     */
    public function testAtThrowsIndexOutOfBoundsIfIndexIsOutOfBounds(string $source, int $index)
    {
        $sequence = Sequence::fromSource($source);

        $this->expectException(Exception\IndexOutOfBounds::class);

        $sequence->at($index);
    }

    public function providerIndexOutOfBounds(): \Generator
    {
        $source = \file_get_contents(__FILE__);
        $tokens = \token_get_all(
            $source,
            TOKEN_PARSE
        );
        $count = \count($tokens);

        $indexes = [
            'less-than-zero' => -1,
            'count' => $count,
            'greater-than-count' => $count + 1,
        ];

        foreach ($indexes as $key => $index) {
            yield $key => [
                $source,
                $index,
            ];
        }
    }

    public function testAtReturnsTokenAtIndex()
    {
        $source = \file_get_contents(__FILE__);
        $tokens = \token_get_all(
            $source,
            TOKEN_PARSE
        );

        $sequence = Sequence::fromSource($source);

        $index = $this->faker()->numberBetween(
            0,
            \count($tokens) - 1
        );

        $expectedToken = Token::fromValue(
            $index,
            $tokens[$index]
        );

        $this->assertEquals($expectedToken, $sequence->at($index));
    }
}
