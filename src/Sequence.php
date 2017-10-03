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

namespace Localheinz\Token;

final class Sequence implements \Countable
{
    const DIRECTION_FORWARD = 1;
    const DIRECTION_BACKWARD = -1;

    /**
     * @var Token[]
     */
    private $tokens;

    /**
     * @var int
     */
    private $count;

    private function __construct(array $tokens)
    {
        $this->tokens = \array_map(function (int $index, $value) {
            return Token::fromValue(
                $index,
                $value
            );
        }, \array_keys($tokens), \array_values($tokens));

        $this->count = \count($tokens);
    }

    /**
     * Returns a new sequence of tokens from the source.
     *
     * @param string $source
     *
     * @return Sequence
     */
    public static function fromSource(string $source): Sequence
    {
        return new self(\token_get_all(
            $source,
            TOKEN_PARSE
        ));
    }

    /**
     * Returns the token at the index in the token sequence.
     *
     * @param int $index
     *
     * @throws Exception\IndexOutOfBounds
     *
     * @return Token
     */
    public function at(int $index): Token
    {
        if ($this->isOutOfBounds($index)) {
            throw Exception\IndexOutOfBounds::fromCountAndIndex(
                $this->count,
                $index
            );
        }

        return $this->tokens[$index];
    }

    /**
     * Returns the next significant token in the token sequence before the index.
     *
     * @param int $index
     *
     * @throws Exception\IndexOutOfBounds
     * @throws Exception\NoSignificantTokenFound
     *
     * @return Token
     */
    public function significantBefore(int $index): Token
    {
        return $this->significantIn(
            self::DIRECTION_BACKWARD,
            $index
        );
    }

    /**
     * Returns the next significant token in the token sequence after the index.
     *
     * @param int $index
     *
     * @throws Exception\IndexOutOfBounds
     * @throws Exception\NoSignificantTokenFound
     *
     * @return Token
     */
    public function significantAfter(int $index): Token
    {
        return $this->significantIn(
            self::DIRECTION_FORWARD,
            $index
        );
    }

    /**
     * Returns the number of tokens in the sequence.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Returns true if the index is out of the bounds of the token sequence.
     *
     * @param int $index
     *
     * @return bool
     */
    private function isOutOfBounds(int $index): bool
    {
        return 0 > $index || $this->count <= $index;
    }

    /**
     * Returns the next significant token starting from the index into the direction.
     *
     * @param int $direction
     * @param int $index
     *
     * @throws Exception\IndexOutOfBounds
     * @throws Exception\NoSignificantTokenFound
     *
     * @return Token
     */
    private function significantIn(int $direction, int $index): Token
    {
        if ($this->isOutOfBounds($index)) {
            throw Exception\IndexOutOfBounds::fromCountAndIndex(
                $this->count,
                $index
            );
        }

        for ($current = $index + $direction; 0 <= $current && $current < $this->count; $current += $direction) {
            $token = $this->tokens[$current];

            if (!$token->isType(T_COMMENT, T_DOC_COMMENT, T_WHITESPACE)) {
                return $token;
            }
        }

        throw Exception\NoSignificantTokenFound::in(
            $direction,
            $index
        );
    }
}