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
}
