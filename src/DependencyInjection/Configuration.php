<?php

namespace Bike\Dashboard\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bike_dashboard');

        $rootNode
            ->children()
                ->arrayNode('dao')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('conn_id')->end()
                            ->scalarNode('db_name')->end()
                            ->scalarNode('prefix')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('redis')
                    ->children()
                        ->arrayNode('connections')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('host')->end()
                                    ->scalarNode('port')->end()
                                    ->scalarNode('timeout')->end()
                                    ->scalarNode('password')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('dao')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('conn_id')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('mongodb')
                    ->children()
                        ->arrayNode('connections')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('host')->end()
                                    ->scalarNode('port')->end()
                                    ->scalarNode('timeout')->end()
                                    ->scalarNode('password')->end()
                                ->end()
                            ->end()
                        ->end()
                        ->arrayNode('dao')
                            ->prototype('array')
                                ->children()
                                    ->scalarNode('conn_id')->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
