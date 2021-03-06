<?php

namespace MDobak\DoctrineDynamicShardsBundle\Doctrine\DBAL;

use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Sharding\PoolingShardConnection;
use MDobak\DoctrineDynamicShardsBundle\Shard\ShardRegistryInterface;

/**
 * Class DynamicShardConnection
 *
 * @author Michał Dobaczewski <mdobak@gmail.com>
 */
class DynamicShardConnection extends PoolingShardConnection implements DynamicShardConnectionInterface
{
    /**
     * @var array
     */
    private $knownShards = [];

    /**
     * @var ShardRegistryInterface
     */
    private $shardRegistry;

    /**
     * @var array
     */
    private $globalParams;

    /**
     * @param array                         $params
     * @param \Doctrine\DBAL\Driver         $driver
     * @param \Doctrine\DBAL\Configuration  $config
     * @param \Doctrine\Common\EventManager $eventManager
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $params, Driver $driver, Configuration $config = null, EventManager $eventManager = null)
    {
        $params['shards'] = $params['shards'] ?? [];
        $params['global'] = $params['global'] ?? $params;

        if (isset($params['driverOptions']['shard_registry'])) {
            $this->shardRegistry = $params['driverOptions']['shard_registry'];
        }

        parent::__construct($params, $driver, $config, $eventManager);

        $this->globalParams = array_merge($params, $params['global']);

        $this->knownShards = [0]; // Shard "0" maps to "global" config on PoolingShardConnection.
        foreach ($params['shards'] as $shard) {
            $this->knownShards[] = $shard['id'];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParams()
    {
        return isset($this->knownShards[$this->getActiveShardId()]) ? $this->getParams() : $this->getParamsForShard($this->getActiveShardId());
    }

    /**
     * Connects to a specific connection.
     *
     * @param string $shardId
     *
     * @return \Doctrine\DBAL\Driver\Connection
     */
    protected function connectTo($shardId)
    {
        if (in_array($shardId, $this->knownShards, true)) {
            return parent::connectTo($shardId);
        }

        $connectionParams = $this->getParamsForShard($shardId);

        $user          = isset($connectionParams['user'])     ? $connectionParams['user']     : null;
        $password      = isset($connectionParams['password']) ? $connectionParams['password'] : null;
        $driverOptions = isset($params['driverOptions'])      ? $params['driverOptions']      : [];

        return $this->_driver->connect($connectionParams, $user, $password, $driverOptions);
    }

    /**
     * @param $shardId
     *
     * @return array
     */
    protected function getParamsForShard($shardId): array
    {
        $params = $this->globalParams;
        $shard  = $this->shardRegistry->getShard($shardId);

        return array_merge($params, $shard);
    }
}
