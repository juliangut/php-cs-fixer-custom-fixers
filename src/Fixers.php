<?php

/*
 * (c) 2021-2022 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

namespace Jgut\PhpCsFixerCustomFixers;

use IteratorAggregate;
use PhpCsFixer\Fixer\FixerInterface;
use ReflectionClass;
use Symfony\Component\Finder\Finder;
use Traversable;

/**
 * @template-implements IteratorAggregate<int, FixerInterface>
 */
class Fixers implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        $finder = Finder::create()
            ->in(__DIR__)
            ->name('*.php');

        $files = array_map(
            fn ($file) => $file->getPathname(),
            iterator_to_array($finder)
        );
        sort($files);

        foreach ($files as $file) {
            $fixerClass = str_replace('/', '\\', mb_substr($file, mb_strlen(__DIR__) - 21, -4));

            if (!class_exists($fixerClass)) {
                continue;
            }

            $reflectionClass = new ReflectionClass($fixerClass);
            if (
                $reflectionClass->isAbstract()
                || !$reflectionClass->implementsInterface(FixerInterface::class)
            ) {
                continue;
            }

            /** @var class-string<FixerInterface> $fixerClass */
            yield new $fixerClass();
        }
    }
}
