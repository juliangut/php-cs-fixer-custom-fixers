<?php

/*
 * (c) 2021-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Fixer\Comment;

use Jgut\PhpCsFixerCustomFixers\Fixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

class LeadingUppercaseCommentFixer extends AbstractFixer
{
    private const PRIORITY = 27;

    /** @see https://regex101.com/r/U0wFG2/1 */
    private const LINE_COMMENT_REGEX = '#^(?<lead>(?:\/\/|\#(?!\[)) *)(?<comment>.+)#u';

    /** @see https://regex101.com/r/j1TWOt/1 */
    private const BLOCK_COMMENT_REGEX = '#^(?<lead>\/\* *\n?(?: *\** *)?)(?<comment>.+(?!\*\/))(?<tail>\n? *\*\/)$#us';

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Comments must start with an uppercase letter.',
            [
                new CodeSample(
                    "<?php\n// line comment\n"
                ),
                new CodeSample(
                    "<?php\n// line comment\n",
                ),
                new CodeSample(
                    "<?php\n/* block comment */\n",
                ),
                new CodeSample(<<<CODE
<?php
/*
 * block comment
 * 
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
        return $tokens->isTokenKindFound(\T_COMMENT);
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index > 0; --$index) {
            /** @var Token $token */
            $token = $tokens[$index];
            if (!$token->isGivenKind([\T_COMMENT])) {
                continue;
            }

            $modifiedContent = $this->applyFixerToComment($token);

            if ($modifiedContent === $token->getContent()) {
                continue;
            }

            $tokens->offsetSet($index, new Token([(int) $token->getId(), $modifiedContent]));
        }
    }

    protected function applyFixerToComment(Token $commentToken): string
    {
        $originalContent = $commentToken->getContent();

        if (preg_match(self::BLOCK_COMMENT_REGEX, $originalContent, $matches) === 1) {
            return $matches['lead'] . ucfirst($matches['comment']) . $matches['tail'];
        }

        if (preg_match(self::LINE_COMMENT_REGEX, $originalContent, $matches) === 1) {
            return $matches['lead'] . ucfirst($matches['comment']);
        }

        return $originalContent;
    }
}
