<?php

/*
 * (c) 2021-2026 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Tests\Fixer\LanguageConstruct;

use Jgut\PhpCsFixerCustomFixers\Fixer\LanguageConstruct\FloatLeadingZeroFixer;
use Jgut\PhpCsFixerCustomFixers\Tests\Fixer\AbstractFixerTestCase;
use PhpCsFixer\ConfigurationException\InvalidFixerConfigurationException;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;

class FloatLeadingZeroFixerTest extends AbstractFixerTestCase
{
    public function testInvalidConfigurationValue(): void
    {
        $fixer = $this->fixer;
        \assert($fixer instanceof ConfigurableFixerInterface);

        $this->expectException(InvalidFixerConfigurationException::class);

        $fixer->configure([FloatLeadingZeroFixer::LEADING_ZERO_CONFIG => 'invalid']);
    }

    public function testInvalidConfigurationOption(): void
    {
        $fixer = $this->fixer;
        \assert($fixer instanceof ConfigurableFixerInterface);

        $this->expectException(InvalidFixerConfigurationException::class);

        $fixer->configure(['unknown_option' => FloatLeadingZeroFixer::LEADING_ZERO_REMOVE]);
    }

    /**
     * @dataProvider fixCasesProvider
     *
     * @param array<string, mixed> $config
     */
    public function testFix(string $expected, ?string $input = null, array $config = []): void
    {
        if ($this->fixer instanceof ConfigurableFixerInterface) {
            $this->fixer->configure($config);
        }

        $this->doTest($expected, $input);
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public static function fixCasesProvider(): array
    {
        return [
            'leading zero' => [
                '<?php $floatVariable = 0.1;',
            ],
            'leading zero with config' => [
                '<?php $floatVariable = 0.1;',
                null,
                [FloatLeadingZeroFixer::LEADING_ZERO_CONFIG => FloatLeadingZeroFixer::LEADING_ZERO_ADD],
            ],
            'add leading zero' => [
                '<?php $floatVariable = 0.1;',
                '<?php $floatVariable = .1;',
            ],
            'add leading zero with config' => [
                '<?php $floatVariable = 0.1;',
                '<?php $floatVariable = .1;',
                [FloatLeadingZeroFixer::LEADING_ZERO_CONFIG => FloatLeadingZeroFixer::LEADING_ZERO_ADD],
            ],
            'non leading zero with config' => [
                '<?php $floatVariable = .1;',
                null,
                [FloatLeadingZeroFixer::LEADING_ZERO_CONFIG => FloatLeadingZeroFixer::LEADING_ZERO_REMOVE],
            ],
            'remove leading zero with config' => [
                '<?php $floatVariable = .1;',
                '<?php $floatVariable = 0.1;',
                [FloatLeadingZeroFixer::LEADING_ZERO_CONFIG => FloatLeadingZeroFixer::LEADING_ZERO_REMOVE],
            ],
        ];
    }
}
