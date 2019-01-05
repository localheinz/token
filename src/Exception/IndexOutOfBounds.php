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

namespace Localheinz\Token\Exception;

final class IndexOutOfBounds extends \OutOfBoundsException
{
    /**
     * @var int
     */
    private $count;

    /**
     * @var int
     */
    private $index;

    /**
     * Returns a new exception from a total count of tokens in a sequence and an index.
     *
     * @param int $count
     * @param int $index
     *
     * @return self
     */
    public static function fromCountAndIndex(int $count, int $index): self
    {
        $exception = new self(\sprintf(
            'Index needs to be equal to or greater than "%d" and less than "%d", but "%d" is not.',
            0,
            $count,
            $index
        ));

        $exception->count = $count;
        $exception->index = $index;

        return $exception;
    }

    /**
     * Returns the count of tokens in the token sequence.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->count;
    }

    /**
     * Returns the index that was attempted to access in the token sequence.
     *
     * @return int
     */
    public function index(): int
    {
        return $this->index;
    }
}
