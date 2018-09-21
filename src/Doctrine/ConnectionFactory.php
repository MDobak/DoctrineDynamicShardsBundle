<?php

namespace MDobak\DoctrineDynamicShardsBundle\Doctrine;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use MDobak\DoctrineDynamicShardsBundle\Shard\ShardRegistryInterface;

/**
 * Class ConnectionFactory
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class ConnectionFactory extends \Doctrine\Bundle\DoctrineBundle\ConnectionFactory
{
    /**
     * @var ShardRegistryInterface
     */
    protected $defaultShardRegistry;

    /**
     * @var ShardRegistryInterface[]
     */
    protected $shardRegistryServices;

    /**
     * ConnectionFactory constructor.
     *
     * @param                          $typesConfig
     * @param ShardRegistryInterface   $defaultShardRegistry
     * @param ShardRegistryInterface[] $shardRegistryServices
     */
    public function __construct($typesConfig, ShardRegistryInterface $defaultShardRegistry, $shardRegistryServices = [])
    {
        parent::__construct($typesConfig);

        $this->defaultShardRegistry  = $defaultShardRegistry;
        $this->shardRegistryServices = $shardRegistryServices;
    }

    /**
     * Create a connection by name.
     *
     * @param mixed[]         $params
     * @param string[]|Type[] $mappingTypes
     *
     * @return Connection
     */
    public function createConnection(array $params, Configuration $config = null, EventManager $eventManager = null, array $mappingTypes = [])
    {
        $shardRegistry            = null;
        $shardRegistryServiceName = $params['driverOptions']['shard_registry_service'] ?? '';

        if ($shardRegistryServiceName) {
            $shardRegistry = $this->shardRegistryServices[$shardRegistryServiceName];
        } else {
            $shardRegistry = $this->defaultShardRegistry;
        }

        if ($shardRegistry) {
            $params['driverOptions']['shard_registry'] = $shardRegistry;
        }

        return parent::createConnection($params, $config, $eventManager, $mappingTypes);
    }
}
