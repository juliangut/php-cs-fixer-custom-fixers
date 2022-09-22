<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Tests\Fixer\LanguageConstruct;

use Jgut\PhpCsFixerCustomFixers\Fixer\LanguageConstruct\FloatLeadingZeroFixer;
use Jgut\PhpCsFixerCustomFixers\Tests\Fixer\AbstractFixerTestCase;

class FloatLeadingZeroFixerTest extends AbstractFixerTestCase
{
    /**
     * @dataProvider fixCasesProvider
     *
     * @param array<string, mixed> $config
     */
    public function testFix(string $expected, ?string $input = null, array $config = []): void
    {
        $this->fixer->configure($config);

        $this->doTest($expected, $input);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function fixCasesProvider(): array
    {
        return [
            'leading zero' => [
                '<?php $floatVariable = 0.1;',
            ],
            'leading zero with config' => [
                '<?php $floatVariable = 0.1;',
                null,
                ['leading_zero' => 'add'],
            ],
            'add leading zero' => [
                '<?php $floatVariable = 0.1;',
                '<?php $floatVariable = .1;',
            ],
            'add leading zero with config' => [
                '<?php $floatVariable = 0.1;',
                '<?php $floatVariable = .1;',
                ['leading_zero' => 'add'],
            ],
            'non leading zero with config' => [
                '<?php $floatVariable = .1;',
                null,
                ['leading_zero' => 'remove'],
            ],
            'remove leading zero with config' => [
                '<?php $floatVariable = .1;',
                '<?php $floatVariable = 0.1;',
                ['leading_zero' => 'remove'],
            ],
        ];
    }
}
