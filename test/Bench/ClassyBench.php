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

namespace Localheinz\Token\Test\Bench;

use Localheinz\Token\Sequence;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

final class ClassyBench
{
    /**
     * @Revs(100)
     */
    public function benchTokenGetAllFromSource(): void
    {
        \token_get_all(
            $this->source(),
            \TOKEN_PARSE
        );
    }

    /**
     * @Revs(100)
     */
    public function benchSequenceFromSource(): void
    {
        Sequence::fromSource($this->source());
    }

    private function source(): string
    {
        static $source;

        if (null === $source) {
            $source = \file_get_contents(__DIR__ . '/../../vendor/phpunit/phpunit/src/Framework/TestCase.php');
        }

        return $source;
    }
}
