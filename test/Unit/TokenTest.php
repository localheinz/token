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

namespace Localheinz\Token\Test\Unit;

use Localheinz\Test\Util\Helper;
use Localheinz\Token\Token;
use PHPUnit\Framework;

/**
 * @internal
 */
final class TokenTest extends Framework\TestCase
{
    use Helper;

    public function testFromTypeAndContentReturnsToken(): void
    {
        $faker = $this->faker();

        $index = $faker->randomNumber();
        $type = \T_STRING;
        $content = $faker->sentence();

        $token = Token::fromTypeAndContent(
            $index,
            $type,
            $content
        );

        self::assertInstanceOf(Token::class, $token);

        self::assertSame($index, $token->index());
        self::assertSame($type, $token->type());
        self::assertSame($content, $token->content());
    }

    public function testFromStringReturnsToken(): void
    {
        $faker = $this->faker();

        $index = $faker->randomNumber();
        $content = $faker->sentence();

        $token = Token::fromString(
            $index,
            $content
        );

        self::assertInstanceOf(Token::class, $token);

        self::assertSame($index, $token->index());
        self::assertSame(\T_STRING, $token->type());
        self::assertSame($content, $token->content());
    }

    /**
     * @dataProvider providerFromValue
     *
     * @param int          $index
     * @param array|string $value
     * @param Token        $expected
     */
    public function testFromValueReturnsToken(int $index, $value, Token $expected): void
    {
        $token = Token::fromValue(
            $index,
            $value
        );

        self::assertEquals($expected, $token);
    }

    public function providerFromValue(): \Generator
    {
        $faker = $this->faker();

        $index = $faker->randomNumber();
        $type = \T_STRING;
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

        foreach ($values as $key => [$value, $expected]) {
            yield $key => [
                $index,
                $value,
                $expected,
            ];
        }
    }

    public function testIsTypeReturnsFalseIfTypeIsDifferent(): void
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $faker->sentence()
        );

        $type = \T_CLASS;

        self::assertFalse($token->isType($type));
    }

    public function testIsTypeReturnsFalseIfAllTypesAreDifferent(): void
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $faker->sentence()
        );

        $types = [
            \T_CLASS,
            \T_INTERFACE,
            \T_TRAIT,
        ];

        self::assertFalse($token->isType(...$types));
    }

    public function testIsTypeReturnsTrueIfTypeIsSame(): void
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $faker->sentence()
        );

        $type = \T_STRING;

        self::assertTrue($token->isType($type));
    }

    public function testIsTypeReturnsTrueIfOneTypeIsSame(): void
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $faker->sentence()
        );

        $types = [
            \T_CLASS,
            \T_INTERFACE,
            \T_STRING,
            \T_TRAIT,
        ];

        self::assertTrue($token->isType(...$types));
    }

    public function testIsContentReturnsFalseIfContentIsDifferent(): void
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $faker->sentence()
        );

        $content = $faker->sentence();

        self::assertFalse($token->isContent($content));
    }

    public function testIsContentReturnsFalseIfAllContentsAreDifferent(): void
    {
        $faker = $this->faker();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $faker->sentence()
        );

        $contents = [
            $faker->sentence(),
            $faker->sentence(),
            $faker->sentence(),
        ];

        self::assertFalse($token->isContent(...$contents));
    }

    public function testIsContentReturnsTrueIfContentIsSame(): void
    {
        $faker = $this->faker();

        $content = $faker->sentence();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $content
        );

        self::assertTrue($token->isContent($content));
    }

    public function testIsContentReturnsTrueIfOneContentIsSame(): void
    {
        $faker = $this->faker();

        $content = $faker->sentence();

        $token = Token::fromTypeAndContent(
            $faker->randomNumber(),
            \T_STRING,
            $content
        );

        $contents = [
            $faker->sentence(),
            $content,
            $faker->sentence(),
        ];

        self::assertTrue($token->isContent(...$contents));
    }
}
