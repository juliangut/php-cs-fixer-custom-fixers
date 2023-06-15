<?php

/*
 * (c) 2021-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Tests\Fixer;

use InvalidArgumentException;
use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use PHPUnit\Framework\Constraint\IsIdentical;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

abstract class AbstractFixerTestCase extends TestCase
{
    protected AbstractFixer $fixer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->fixer = $this->getTestFixer();
    }

    protected function getTestFixer(): AbstractFixer
    {
        $fixerClassName = preg_replace(
            '/^(Jgut\\\\PhpCsFixerCustomFixers)\\\\Tests(\\\\.+)Test$/',
            '$1$2',
            static::class
        );

        return new $fixerClassName();
    }

    protected function doTest(string $expected, ?string $input = null): void
    {
        if ($expected === $input) {
            throw new InvalidArgumentException('Input parameter must not be equal to expected parameter.');
        }

        $file = new SplFileInfo(__FILE__);
        $fileIsSupported = $this->fixer->supports($file);

        Tokens::clearCache();
        $expectedTokens = Tokens::fromCode($expected);

        if ($fileIsSupported) {
            $this->fixer->fix($file, $expectedTokens);
        }

        static::assertThat(
            $expectedTokens->generateCode(),
            new IsIdentical($expected),
            'Expected code should be already fixed.',
        );
        static::assertFalse(
            $expectedTokens->isChanged(),
            'Tokens collection built on expected code must not be marked as changed after fixing.',
        );

        if ($input !== null) {
            Tokens::clearCache();
            $inputTokens = Tokens::fromCode($input);

            if ($fileIsSupported) {
                static::assertTrue(
                    $this->fixer->isCandidate($inputTokens),
                    'Fixer must be a candidate for input code.',
                );
                static::assertFalse($inputTokens->isChanged(), 'Fixer must not touch Tokens on candidate check.');

                $this->fixer->fix($file, $inputTokens);
            }

            static::assertTrue(
                $inputTokens->isChanged(),
                'Tokens collection built on input code must be marked as changed after fixing.',
            );
            static::assertThat(
                $inputTokens->generateCode(),
                new IsIdentical($expected),
                'Code build on input code must match expected code.',
            );

            Tokens::clearCache();

            static::assertSameTokens($expectedTokens, $inputTokens);
        }
    }

    protected static function assertSameTokens(Tokens $expectedTokens, Tokens $inputTokens): void
    {
        /** @var Token $expectedToken */
        foreach ($expectedTokens as $index => $expectedToken) {
            if (!\array_key_exists($index, iterator_to_array($inputTokens))) {
                static::fail(sprintf(
                    'The token at index %d must be "%s", but is not set in the input collection.',
                    $index,
                    $expectedToken->toJson(),
                ));
            }

            /** @var Token $inputToken */
            $inputToken = $inputTokens[$index];

            static::assertTrue(
                $expectedToken->equals($inputToken),
                sprintf(
                    'The token at index %d must be "%s", got "%s".',
                    $index,
                    $expectedToken->toJson(),
                    $inputToken->toJson(),
                ),
            );

            $expectedTokenKind = $expectedToken->isArray()
                ? (int) $expectedToken->getId()
                : $expectedToken->getContent();
            static::assertTrue(
                $inputTokens->isTokenKindFound($expectedTokenKind),
                sprintf(
                    'The token kind %s (%s) must be found in tokens collection.',
                    $expectedTokenKind,
                    \is_string($expectedTokenKind) ? $expectedTokenKind : Token::getNameForId($expectedTokenKind),
                ),
            );
        }

        static::assertCount(
            $expectedTokens->count(),
            $inputTokens,
            'Both collections must have the same length.',
        );
    }
}
