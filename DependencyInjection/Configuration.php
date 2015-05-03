<?php

/*
 * This file is part of the XabbuhDIConfigurationBundle package.
 *
 * (c) Christian Flothmann <christian.flothmann@xabbuh.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Xabbuh\DIConfigurationBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Christian Flothmann <christian.flothmann@xabbuh.de>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('xabbuh_di_configuration');
        $rootNode
            ->useAttributeAsKey('id')
            ->prototype('array')
                ->beforeNormalization()
                    ->ifString()
                    ->then(function ($v) { return array('class' => $v); })
                ->end()
                ->children()
                    ->scalarNode('class')->end()
                    ->arrayNode('arguments')
                        ->beforeNormalization()
                            ->always()
                            ->then(function ($v) {
                                $values = array();
                                $index = 0;

                                foreach ($v as $value) {
                                    if (is_array($value)) {
                                        $index = $value['index'];
                                        $value = $value['value'];
                                    }

                                    $values[] = array(
                                        'index' => $index++,
                                        'value' => $value,
                                    );
                                }

                                return $values;
                            })
                        ->end()
                        ->prototype('array')
                            ->children()
                                ->integerNode('index')->end()
                                ->scalarNode('value')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
