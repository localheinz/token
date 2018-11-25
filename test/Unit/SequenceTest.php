<?php

declare(strict_types=1);

/**
 * Copyright (c) 2017 Andreas Möller.
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 * @see https://github.com/localheinz/token
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

    public function testFromSourceUsesTokenParse()
    {
        $source = <<<'PHP'
<?php

final class Example
{
    public function class(): string
    {
        return self::class;
    }
}
PHP;

        $sequence = Sequence::fromSource($source);

        $classTokens = [];

        for ($index = 0; $index < $sequence->count(); ++$index) {
            $token = $sequence->at($index);

            if ($token->isType(T_CLASS)) {
                $classTokens[] = $token;
            }
        }

        $this->assertCount(1, $classTokens);
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

    /**
     * @dataProvider providerIndexOutOfBounds
     *
     * @param string $source
     * @param int    $index
     */
    public function testSignificantBeforeThrowsIndexOutOfBoundsIfIndexIsOutOfBounds(string $source, int $index)
    {
        $sequence = Sequence::fromSource($source);

        $this->expectException(Exception\IndexOutOfBounds::class);

        $sequence->significantBefore($index);
    }

    public function testSignificantBeforeThrowsNoSignificantTokenFoundIfNoSignificantTokenFound()
    {
        $source = <<<'PHP'
<?php

namespace Foo;

class Bar 
{
}
PHP;

        $index = 0;

        $sequence = Sequence::fromSource($source);

        $this->expectException(Exception\NoSignificantTokenFound::class);
        $this->expectExceptionMessage(\sprintf(
            'Could not find a significant token before index "%d".',
            $index
        ));

        $sequence->significantBefore($index);
    }

    /**
     * @dataProvider providerSignificantBefore
     *
     * @param string $source
     * @param int    $index
     * @param int    $indexSignificantBefore
     */
    public function testSignificantBeforeReturnsSignificantTokenBeforeIndex(string $source, int $index, int $indexSignificantBefore)
    {
        $sequence = Sequence::fromSource($source);

        $token = $sequence->significantBefore($index);

        $this->assertEquals($sequence->at($indexSignificantBefore), $token);
    }

    public function providerSignificantBefore(): \Generator
    {
        $source = <<<'PHP'
<?php

/**
 * A file-level comment
 */
namespace Foo;

class Bar /* implements Baz */ 
{
    private $bar; // really?

    public function __construct(int $bar) 
    {
        $this->bar = $bar; # makes sense
    }
}
PHP;

        $values = [
            'namespace-to-open-tag' => [
                4,
                0,
            ],
            'namespace-name-to-namespace' => [
                6,
                4,
            ],
            'class-to-semicolon' => [
                9,
                7,
            ],
            'class-opening-brace-to-class-name' => [
                15,
                11,
            ],
            'constructor-visibility-to-semicolon' => [
                24,
                20,
            ],
            'constructor-closing-brace-to-semicolon' => [
                48,
                44,
            ],
        ];

        foreach ($values as $key => [$index, $indexSignificantBefore]) {
            yield $key => [
                $source,
                $index,
                $indexSignificantBefore,
            ];
        }
    }

    /**
     * @dataProvider providerIndexOutOfBounds
     *
     * @param string $source
     * @param int    $index
     */
    public function testSignificantAfterThrowsIndexOutOfBoundsIfIndexIsOutOfBounds(string $source, int $index)
    {
        $sequence = Sequence::fromSource($source);

        $this->expectException(Exception\IndexOutOfBounds::class);

        $sequence->significantAfter($index);
    }

    /**
     * @dataProvider providerNoSignificantAfterFound
     *
     * @param string $source
     * @param int    $index
     */
    public function testSignificantAfterThrowsNoSignificantTokenFoundIfNoSignificantTokenFound(string $source, int $index)
    {
        $sequence = Sequence::fromSource($source);

        $this->expectException(Exception\NoSignificantTokenFound::class);
        $this->expectExceptionMessage(\sprintf(
            'Could not find a significant token after index "%d".',
            $index
        ));

        $sequence->significantAfter($index);
    }

    public function providerNoSignificantAfterFound(): \Generator
    {
        $source = <<<'PHP'
<?php

namespace Foo;

class Bar 
{
}

/* a comment*/
PHP;

        $tokens = \token_get_all(
            $source,
            TOKEN_PARSE
        );

        $indexes = [
            'index-max' => \count($tokens) - 1,
            'index-class-closing-brace' => 13,
        ];

        foreach ($indexes as $key => $index) {
            yield $key => [
                $source,
                $index,
            ];
        }
    }

    /**
     * @dataProvider providerSignificantAfter
     *
     * @param string $source
     * @param int    $index
     * @param int    $indexSignificantAfter
     */
    public function testSignificantAfterReturnsSignificantTokenAfterIndex(string $source, int $index, int $indexSignificantAfter)
    {
        $sequence = Sequence::fromSource($source);

        $token = $sequence->significantAfter($index);

        $this->assertEquals($sequence->at($indexSignificantAfter), $token);
    }

    public function providerSignificantAfter(): \Generator
    {
        $source = <<<'PHP'
<?php

/**
 * A file-level comment
 */
namespace Foo;

class Bar /* implements Baz */ 
{
    private $bar; // really?

    public function __construct(int $bar) 
    {
        $this->bar = $bar; # makes sense
    }
}
PHP;

        $values = [
            'open-tag-to-namespace' => [
                0,
                4,
            ],
            'namespace-to-namespace-name' => [
                4,
                6,
            ],
            'semicolon-to-class' => [
                7,
                9,
            ],
            'class-name-to-opening-brace' => [
                11,
                15,
            ],
            'semicolon-to-constructor-visibility' => [
                20,
                24,
            ],
            'semicolon-to-constructor-closing-brace' => [
                44,
                48,
            ],
        ];

        foreach ($values as $key => [$index, $indexSignificantAfter]) {
            yield $key => [
                $source,
                $index,
                $indexSignificantAfter,
            ];
        }
    }
}
