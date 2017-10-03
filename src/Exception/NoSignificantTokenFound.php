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

namespace Localheinz\Token\Exception;

final class NoSignificantTokenFound extends \RuntimeException
{
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
        return new self(\sprintf(
            'Could not find a significant token %s index "%d".',
            0 > $direction ? 'before' : 'after',
            $index
        ));
    }
}
