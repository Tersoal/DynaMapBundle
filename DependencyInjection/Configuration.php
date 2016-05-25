<?php

namespace Tersoal\DynaMapBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('tersoal_dyna_map');

        $rootNode
            ->children()
                ->scalarNode('route_parameter')->defaultValue('model_id')->end()
                ->scalarNode('master_entity')->isRequired()->cannotBeEmpty()->end()
                ->scalarNode('field_entity')->isRequired()->cannotBeEmpty()->end()
                ->arrayNode('field_white_list')
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('field_black_list')
                    ->prototype('scalar')->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
