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

namespace Localheinz\Token\Exception;

final class NoSignificantTokenFound extends \RuntimeException
{
    /**
     * @var int
     */
    private $direction;

    /**
     * @var int
     */
    private $index;

    /**
     * Returns a new exception from a direction in which the next significant token was search for from an index.
     *
     * @param int $direction
     * @param int $index
     *
     * @return self
     */
    public static function in(int $direction, int $index): self
    {
        $exception = new self(\sprintf(
            'Could not find a significant token %s index "%d".',
            0 > $direction ? 'before' : 'after',
            $index
        ));

        $exception->direction = $direction;
        $exception->index = $index;

        return $exception;
    }

    /**
     * Returns the direction in which a significant token was attempted to be found.
     *
     * @return int
     */
    public function direction(): int
    {
        return $this->direction;
    }

    /**
     * Returns the index from which a significant token was attempted to be found.
     *
     * @return int
     */
    public function index(): int
    {
        return $this->index;
    }
}
