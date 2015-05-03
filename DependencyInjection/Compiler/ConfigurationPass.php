<?php

/*
 * This file is part of the XabbuhDIConfigurationBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\DIConfigurationBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class ConfigurationPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasParameter('xabbuh_di_configuration.services')) {
            return;
        }

        $services = $container->getParameter('xabbuh_di_configuration.services');

        foreach ($services as $id => $config) {
            if (!$container->hasDefinition($id)) {
                throw new \RuntimeException(sprintf('A service with id "%s" is not registered.', $id));
            }

            $definition = $container->getDefinition($id);

            if (isset($config['class'])) {
                $definition->setClass($config['class']);
            }

            if (isset($config['arguments'])) {
                foreach ($config['arguments'] as $argument) {
                    if ('@' === substr($argument['value'], 0, 1)) {
                        $value = new Reference(substr($argument['value'], 1));
                    } else {
                        $value = $argument['value'];
                    }

                    $definition->replaceArgument($argument['index'], $value);
                }
            }
        }
    }
}
