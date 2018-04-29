<?php

namespace MDobak\DoctrineDynamicShardsBundle\DependencyInjection\Compiler;

use MDobak\DoctrineDynamicShardsBundle\Doctrine\ConnectionFactory;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class ConnectionFactoryCompilerPass.
 *
 * @author Michał Dobaczewski <mdobak@gmail.com>
 */
class ConnectionFactoryCompilerPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $defaultShardRegistryService = new Reference($container->getParameter('mdobak.default_shard_registry_service'));
        $shardRegistryServices       = [];

        foreach ($container->getParameter('mdobak.connections') as $connection) {
            $shardRegistryService = $connection['shard_registry_service'];
            if ($shardRegistryService) {
                $shardRegistryServices[$shardRegistryService] = new Reference($shardRegistryService);
            }
        }

        $container
            ->findDefinition('doctrine.dbal.connection_factory')
            ->setClass(ConnectionFactory::class)
            ->addArgument($defaultShardRegistryService)
            ->addArgument($shardRegistryServices)
        ;
    }
}
