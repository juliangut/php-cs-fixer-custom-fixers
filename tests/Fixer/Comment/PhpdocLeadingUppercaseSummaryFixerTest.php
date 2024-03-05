<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Tests\Fixer\Comment;

use Jgut\PhpCsFixerCustomFixers\Tests\Fixer\AbstractFixerTestCase;

class PhpdocLeadingUppercaseSummaryFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider fixCasesProvider
     */
    public function testFix(string $expected, ?string $input = null): void
    {
        $this->doTest($expected, $input);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function fixCasesProvider(): array
    {
        return [
            'uppercase line docblock' => [
                '<?php /** Docblock comment */',
            ],
            'lowercase line docblock' => [
                '<?php /** Docblock comment */',
                '<?php /** docblock comment */',
            ],
            'uppercase docblock' => [
                '<?php
/**
 * Docblock summary
 *
 * @param string $variable
 */',
            ],
            'lowercase docblock' => [
                '<?php
/**
 * Docblock summary
 *
 * @param string $variable
 */',
                '<?php
/**
 * docblock summary
 *
 * @param string $variable
 */',
            ],
        ];
    }
}
