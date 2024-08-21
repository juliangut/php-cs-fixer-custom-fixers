<?php

/*
 * (c) 2021-2024 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Fixer;

use PhpCsFixer\AbstractFixer as PhpCsFixerAbstractFixer;
use PhpCsFixer\Preg;

abstract class AbstractFixer extends PhpCsFixerAbstractFixer
{
    final public static function name(): string
    {
        $nameParts = explode('\\', static::class);
        $name = substr(end($nameParts), 0, -\strlen('Fixer'));

        return sprintf('JgutCustomFixers/%s', self::camelCaseToUnderscore($name));
    }

    private static function camelCaseToUnderscore(string $string): string
    {
        return mb_strtolower(Preg::replace('/(?<!^)((?=[\p{Lu}][^\p{Lu}])|(?<![\p{Lu}])(?=[\p{Lu}]))/', '_', $string));
    }

    public function getName(): string
    {
        return static::name();
    }
}
