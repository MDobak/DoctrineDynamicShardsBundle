<?php

namespace MDobak\DoctrineDynamicShardsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mdobak_doctrine_dynamic_shards');

        $rootNode
            ->children()
                ->scalarNode('default_shard_registry_service')
                    ->treatNullLike('mdobak.doctrine_dynamic_shard.shard.shard_registry')
                    ->defaultValue('mdobak.doctrine_dynamic_shard.shard.shard_registry')
                ->end()
                ->arrayNode('connections')
                    ->useAttributeAsKey('name')
                    ->arrayPrototype()
                        ->children()
                            ->scalarNode('shard_registry_service')
                                ->defaultNull()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
