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

class LeadingUppercaseCommentFixerTest extends AbstractFixerTestCase
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
    public static function fixCasesProvider(): array
    {
        return [
            // Line slash comments
            'uppercase line slash comment' => [
                '<?php // Line comment',
            ],
            'code with uppercase line slash comment' => [
                '<?php $valid = true; // Line comment',
            ],
            'lowercase line slash comment' => [
                '<?php // Line comment',
                '<?php // line comment',
            ],
            'code with lowercase line slash comment' => [
                '<?php $valid = true; // Line comment',
                '<?php $valid = true; // line comment',
            ],

            // Line hash comment
            'uppercase line hash comment' => [
                '<?php # Line comment',
            ],
            'code with uppercase line hash comment' => [
                '<?php $valid = true; # Line comment',
            ],
            'lowercase line hash comment' => [
                '<?php # Line comment',
                '<?php # line comment',
            ],
            'code with lowercase line hash comment' => [
                '<?php $valid = true; # Line comment',
                '<?php $valid = true; # line comment',
            ],

            // Line block comments
            'uppercase line block comment' => [
                '<?php /* Block comment */',
            ],
            'code with uppercase line block comment' => [
                '<?php $valid = true; /* Block comment */',
            ],
            'code with inline uppercase line block comment' => [
                '<?php $valid = /* Block comment */ true;',
            ],
            'lowercase line block comment' => [
                '<?php /* Block comment */',
                '<?php /* block comment */',
            ],
            'code with lowercase line block comment' => [
                '<?php $valid = true; /* Block comment */',
                '<?php $valid = true; /* block comment */',
            ],
            'code with inline lowercase line block comment' => [
                '<?php $valid = /* Block comment */ true;',
                '<?php $valid = /* block comment */ true;',
            ],

            // Block comments
            'uppercase block comment' => [
                '<?php /*
 Block comment
*/',
            ],
            'code with uppercase block comment' => [
                '<?php $valid = true; /*
 Block comment
*/',
            ],
            'code with inline uppercase block comment' => [
                '<?php $valid = /*
 Block comment
*/
true;',
            ],
            'lowercase block comment' => [
                '<?php /*
Block comment
*/',
                '<?php /*
block comment
*/',
            ],
            'code with lowercase block comment' => [
                '<?php $valid = true; /*
Block comment
*/',
                '<?php $valid = true; /*
block comment
*/',
            ],
            'code with inline lowercase block comment' => [
                '<?php $valid = /*
Block comment
*/
true;',
                '<?php $valid = /*
block comment
*/
true;',
            ],
            'uppercase complete block comment' => [
                '<?php /*
 * Block comment
 *
 * following lines
 */',
            ],
            'code with uppercase complete block comment' => [
                '<?php $valid = true; /*
 * Block comment
 *
 * following lines
 */',
            ],
            'code with inline uppercase complete block comment' => [
                '<?php $valid = /*
 * Block comment
 *
 * following lines
 */
 true;',
            ],
            'lowercase complete block comment' => [
                '<?php /*
 * Block comment
 *
 * following lines
 */',
                '<?php /*
 * block comment
 *
 * following lines
 */',
            ],
        ];
    }
}
