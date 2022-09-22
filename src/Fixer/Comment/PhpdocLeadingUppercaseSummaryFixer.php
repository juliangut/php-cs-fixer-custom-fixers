<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Fixer\Comment;

use Jgut\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use PhpCsFixer\DocBlock\DocBlock;
use PhpCsFixer\DocBlock\ShortDescription;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class PhpdocLeadingUppercaseSummaryFixer extends AbstractFixer
{
    private const PRIORITY = 27;

    /** @see https://regex101.com/r/ZOsFBN/1 */
    private const LINE_DOCBLOCK_REGEX = '#^(?<lead>(?: *\/\*{2}) *)(?<comment>[^\n]+(?!\*\/))(?<tail> *\*\/)$#u';

    /** @see https://regex101.com/r/1zjSz2/1 */
    private const DOCBLOCK_DESCRIPTION_REGEX = '#^(?<lead>(?: *\/?\*) *)(?<comment>.+)$#us';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Docblock summary must start with an uppercase letter.',
            [
                new CodeSample(
                    "<?php\n/** docblock comment */\n"
                ),
                new CodeSample(<<<CODE
<?php
/**
 * docblock summary
 *
 * @throws \RuntimeException 
 */
CODE),
            ]
        );
    }

    public function getPriority(): int
    {
        return self::PRIORITY;
    }

    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(\T_DOC_COMMENT);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            /** @var Token $token */
            $token = $tokens[$index];
            if (!$token->isGivenKind([\T_DOC_COMMENT])) {
                continue;
            }

            $modifiedContent = $this->applyFixerToDocBlock($token);

            if ($modifiedContent === $token->getContent()) {
                continue;
            }

            $tokens->offsetSet($index, new Token([(int) $token->getId(), $modifiedContent]));
        }
    }

    protected function applyFixerToDocBlock(Token $commentToken): string
    {
        $originalContent = $commentToken->getContent();
        $docBlock = new DocBlock($originalContent);

        $end = (new ShortDescription($docBlock))->getEnd();
        if ($end === null) {
            if (preg_match(self::LINE_DOCBLOCK_REGEX, $originalContent, $matches) !== 1) {
                return $originalContent;
            }

            return $matches['lead'] . ucfirst($matches['comment']) . $matches['tail'];
        }

        $line = $docBlock->getLine($end);
        if ($line === null) {
            return $originalContent;
        }

        $commentContent = $line->getContent();
        if (preg_match(self::DOCBLOCK_DESCRIPTION_REGEX, $commentContent, $matches) !== 1) {
            return $originalContent;
        }

        $line->setContent($matches['lead'] . ucfirst($matches['comment']));

        return $docBlock->getContent();
    }
}
