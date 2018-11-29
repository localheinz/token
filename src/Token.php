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

namespace Localheinz\Token;

final class Token
{
    /**
     * @var int
     */
    private $index;

    /**
     * @var int
     */
    private $type;

    /**
     * @var string
     */
    private $content;

    private function __construct(int $index, int $type, string $content)
    {
        $this->index = $index;
        $this->type = $type;
        $this->content = $content;
    }

    /**
     * Returns a new token from index in the token sequence, type, and content.
     *
     * @param int    $index
     * @param int    $type
     * @param string $content
     *
     * @return self
     */
    public static function fromTypeAndContent(int $index, int $type, string $content): self
    {
        return new self(
            $index,
            $type,
            $content
        );
    }

    /**
     * Returns a new token from index in the token sequence, and string content.
     *
     * @param int    $index
     * @param string $string
     *
     * @return self
     */
    public static function fromString(int $index, string $string): self
    {
        return new self(
            $index,
            \T_STRING,
            $string
        );
    }

    /**
     * Returns a new token from index in the token sequence, and a value returned by token_get_all().
     *
     * @see http://php.net/manual/en/function.token-get-all.php
     *
     * @param int          $index
     * @param array|string $value
     *
     * @return self
     */
    public static function fromValue(int $index, $value): self
    {
        if (\is_array($value)) {
            [$type, $content] = $value;

            return self::fromTypeAndContent(
                $index,
                $type,
                $content
            );
        }

        return self::fromString(
            $index,
            $value
        );
    }

    /**
     * Returns the index of the token in the token sequence.
     *
     * @return int
     */
    public function index(): int
    {
        return $this->index;
    }

    /**
     * Returns the type of the token.
     *
     * @see http://php.net/manual/en/tokens.php
     *
     * @return int
     */
    public function type(): int
    {
        return $this->type;
    }

    /**
     * Returns the content of the token.
     *
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * Returns true if the type of the token is any of the types.
     *
     * @param int[] ...$types
     *
     * @return bool
     */
    public function isType(int ...$types): bool
    {
        foreach ($types as $type) {
            if ($this->type === $type) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns true if the content of the token is any of the specified contents.
     *
     * @param string[] ...$contents
     *
     * @return bool
     */
    public function isContent(string ...$contents): bool
    {
        foreach ($contents as $content) {
            if ($this->content === $content) {
                return true;
            }
        }

        return false;
    }
}
