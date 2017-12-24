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
use Localheinz\Token\Token;
use PHPUnit\Framework;

final class TokenTest extends Framework\TestCase
{
    use Helper;

    public function testFromTypeAndContentReturnsToken()
    {
        $faker = $this->faker();

        $index = $faker->randomNumber();
        $type = T_STRING;
        $content = $faker->sentence();

        $token = Token::fromTypeAndContent(
            $index,
            $type,
            $content
        );

        $this->assertInstanceOf(Token::class, $token);

        $this->assertSame($index, $token->index());
        $this->assertSame($type, $token->type());
        $this->assertSame($content, $token->content());
    }

    public function testFromStringReturnsToken()
    {
        $faker = $this->faker();

        $index = $faker->randomNumber();
        $content = $faker->sentence();

        $token = Token::fromString(
            $index,
            $content
        );

        $this->assertInstanceOf(Token::class, $token);

        $this->assertSame($index, $token->index());
        $this->assertSame(T_STRING, $token->type());
        $this->assertSame($content, $token->content());
    }

    /**
     * @dataProvider providerFromValue
     *
     * @param int          $index
     * @param array|string $value
     * @param Token        $expected
     */
    public function testFromValueReturnsToken(int $index, $value, Token $expected)
    {
        $token = Token::fromValue(
            $index,
            $value
        );

        $this->assertEquals($expected, $token);
    }

    public function providerFromValue(): \Generator
    {
        $faker = $this->faker();

        $index = $faker->randomNumber();
        $type = T_STRING;
        $content = $faker->sentence();

        $values = [
            'array' => [
                [
                    $type,
                    $content,
                ],
                Token::fromTypeAndContent(
                    $index,
                    $type,
                    $content
                ),
            ],
            'string' => [
                $content,
                Token::fromString(
                    $index,
                    $content
                ),
            ],
        ];

        foreach ($values as $key => list($value, $expected)) {
            yield $key => [
                $index,
                $value,
                $expected,
            ];
        }
    }

    public function testIsTypeReturnsFalseIfTypeIsDifferent()
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $faker->sentence()
        );

        $type = T_CLASS;

        $this->assertFalse($token->isType($type));
    }

    public function testIsTypeReturnsFalseIfAllTypesAreDifferent()
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $faker->sentence()
        );

        $types = [
            T_CLASS,
            T_INTERFACE,
            T_TRAIT,
        ];

        $this->assertFalse($token->isType(...$types));
    }

    public function testIsTypeReturnsTrueIfTypeIsSame()
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $faker->sentence()
        );

        $type = T_STRING;

        $this->assertTrue($token->isType($type));
    }

    public function testIsTypeReturnsTrueIfOneTypeIsSame()
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $faker->sentence()
        );

        $types = [
            T_CLASS,
            T_INTERFACE,
            T_STRING,
            T_TRAIT,
        ];

        $this->assertTrue($token->isType(...$types));
    }

    public function testIsContentReturnsFalseIfContentIsDifferent()
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $faker->sentence()
        );

        $content = $faker->sentence();

        $this->assertFalse($token->isContent($content));
    }

    public function testIsContentReturnsFalseIfAllContentsAreDifferent()
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $faker->sentence()
        );

        $contents = [
            $faker->sentence(),
            $faker->sentence(),
            $faker->sentence(),
        ];

        $this->assertFalse($token->isContent(...$contents));
    }

    public function testIsContentReturnsTrueIfContentIsSame()
    {
        $faker = $this->faker();

        $content = $faker->sentence();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $content
        );

        $this->assertTrue($token->isContent($content));
    }

    public function testIsContentReturnsTrueIfOneContentIsSame()
    {
        $faker = $this->faker();

        $content = $faker->sentence();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            T_STRING,
            $content
        );

        $contents = [
            $faker->sentence(),
            $content,
            $faker->sentence(),
        ];

        $this->assertTrue($token->isContent(...$contents));
    }
}
