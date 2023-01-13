<?php

/*
 * (c) 2021-2023 Julián Gutiérrez <juliangut@gmail.com>
 *
 * @license BSD-3-Clause
 * @link https://github.com/juliangut/php-cs-fixer-custom-fixers
 */

declare(strict_types=1);

namespace Jgut\PhpCsFixerCustomFixers\Fixer;

use LogicException;
use PhpCsFixer\AbstractFixer as PhpCsFixerAbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionInterface;
use PhpCsFixer\Utils;
use RuntimeException;

abstract class AbstractFixer extends PhpCsFixerAbstractFixer
{
    protected $configuration = [];

    final public static function name(): string
    {
        $nameParts = explode('\\', static::class);
        $name = substr(end($nameParts), 0, -\strlen('Fixer'));

        return sprintf('JgutCustomFixers/%s', Utils::camelCaseToUnderscore($name));
    }

    public function getName(): string
    {
        return static::name();
    }

    /**
     * @throws RuntimeException
     * @throws LogicException
     *
     * @return mixed
     */
    protected function getConfiguration(string $config)
    {
        if (!$this instanceof ConfigurableFixerInterface) {
            throw new LogicException(sprintf(
                'Cannot get configuration using Abstract parent, child not implementing "%s".',
                ConfigurableFixerInterface::class
            ));
        }

        $configExists = count(array_filter(
            $this->getConfigurationDefinition()->getOptions(),
            static fn (FixerOptionInterface $option): bool => $option->getName() === $config
        )) !== 0;
        if (!$configExists) {
            throw new RuntimeException(sprintf('Configuration "%s" does not exist', $config));
        }

        $configurations = $this->configuration ?? [];

        return $configurations[$config];
    }
}
