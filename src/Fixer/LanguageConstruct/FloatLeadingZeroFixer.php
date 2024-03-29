<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Fixer\LanguageConstruct;

use Jgut\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class FloatLeadingZeroFixer extends AbstractFixer implements ConfigurableFixerInterface
{
    private const PRIORITY = -1;
    private const LEADING_ZERO_CONFIG = 'leading_zero';
    private const LEADING_ZERO_ADD = 'add';
    private const LEADING_ZERO_REMOVE = 'remove';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Add/remove leading zero on float values.',
            [
                new CodeSample(
                    "<?php\n\$floatVal = .5\n"
                ),
                new CodeSample(
                    "<?php\n\$floatVal = 0.5\n",
                    [self::LEADING_ZERO_CONFIG => self::LEADING_ZERO_REMOVE]
                ),
            ]
        );
    }

    public function getPriority(): int
    {
        return self::PRIORITY;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(\T_DNUMBER);
    }

    protected function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            (new FixerOptionBuilder(self::LEADING_ZERO_CONFIG, 'Should add or remove leading zero.'))
                ->setAllowedTypes(['string'])
                ->setAllowedValues([self::LEADING_ZERO_ADD, self::LEADING_ZERO_REMOVE])
                ->setDefault(self::LEADING_ZERO_ADD)
                ->getOption(),
        ]);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        /** @var Token $token */
        foreach ($tokens as $index => $token) {
            if (!$token->isGivenKind([\T_DNUMBER])) {
                continue;
            }

            $value = $token->getContent();

            if (
                $this->getConfiguration(self::LEADING_ZERO_CONFIG) === self::LEADING_ZERO_ADD
                && $value[0] === '.'
            ) {
                $tokens->offsetSet($index, new Token([(int) $token->getId(), '0' . $value]));
            } elseif (
                $this->getConfiguration(self::LEADING_ZERO_CONFIG) === self::LEADING_ZERO_REMOVE
                && preg_match('/^0\./', $value) === 1
            ) {
                $tokens->offsetSet($index, new Token([(int) $token->getId(), mb_substr($value, 1)]));
            }
        }
    }
}
