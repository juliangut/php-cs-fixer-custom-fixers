<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

use Jgut\PhpCsFixerCustomFixers\Fixer\Comment\LeadingUppercaseCommentFixer;
use Jgut\PhpCsFixerCustomFixers\Fixer\Comment\PhpdocLeadingUppercaseSummaryFixer;
use Jgut\PhpCsFixerCustomFixers\Fixer\LanguageConstruct\FloatLeadingZeroFixer;
use Jgut\PhpCsFixerCustomFixers\Fixers;
use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$header = <<<'HEADER'
(c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>

@license BSD-3-Clause
@link https://github.com/juliangut/php-cs-fixer-custom-fixers
HEADER;

$finder = Finder::create()
    ->ignoreDotFiles(false)
    ->exclude(['vendor'])
    ->in(__DIR__)
    ->name(__FILE__);

return (new Config())
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setIndent('    ')
    ->setLineEnding("\n")
    ->setFinder($finder)
    ->registerCustomFixers(new Fixers())
    ->setRules([
        '@PSR12' => true,
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'comment',
            'location' => 'after_open',
            'separate' => 'both',
        ],
        FloatLeadingZeroFixer::name() => true,
        LeadingUppercaseCommentFixer::name() => true,
        PhpdocLeadingUppercaseSummaryFixer::name() => true,
    ]);
