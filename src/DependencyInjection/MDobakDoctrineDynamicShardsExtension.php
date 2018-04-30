<?php

namespace MDobak\DoctrineDynamicShardsBundle\DependencyInjection;

use Doctrine\DBAL\Sharding\ShardChoser\MultiTenantShardChoser;
use MDobak\DoctrineDynamicShardsBundle\Doctrine\DBAL\DynamicShardConnection;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Class MDobakDoctrineDynamicShardsExtension
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class MDobakDoctrineDynamicShardsExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('mdobak.default_shard_registry_service', $config['default_shard_registry_service']);
        $container->setParameter('mdobak.connections', $config['connections']);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }

    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        $configs = $container->getExtensionConfig($this->getAlias());
        $config  = $this->processConfiguration(new Configuration(), $configs);

        if (isset($bundles['DoctrineBundle'])) {
            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                    case 'doctrine':
                        $doctrineConfig = ['dbal' => ['connections' => []]];

                        foreach ($config['connections'] as $connectionName => $connectionConfig) {
                            $doctrineConfig['dbal']['connections'][$connectionName] = [
                                'wrapper_class' => DynamicShardConnection::class,
                                'options'       => [
                                    'shard_registry'         => null,
                                    'shard_registry_service' => $connectionConfig['shard_registry_service']
                                ]
                            ];

                            if (isset($connectionConfig['shard_chooser_service'])) {
                                $doctrineConfig['dbal']['connections'][$connectionName]['shard_choser_service']
                                    = $connectionConfig['shard_chooser_service'];
                            } elseif (isset($connectionConfig['shard_chooser'])) {
                                $doctrineConfig['dbal']['connections'][$connectionName]['shard_choser']
                                    = $connectionConfig['shard_chooser'];
                            } else {
                                $doctrineConfig['dbal']['connections'][$connectionName]['shard_choser']
                                    = MultiTenantShardChoser::class;
                            }
                        }

                        $container->prependExtensionConfig($name, $doctrineConfig);
                        break;
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'mdobak_doctrine_dynamic_shards';
    }
}
