<?php

namespace MDobak\DoctrineDynamicShardsBundle\Shard;

use MDobak\DoctrineDynamicShardsBundle\Exception\InvalidShardIdException;

/**
 * Class ShardRegistry.
 *
 * @author Michał Dobaczewski <mdobak@gmail.com>
 */
class ShardRegistry implements ShardRegistryInterface
{
    /**
     * @var Configuration[]
     */
    protected $shards = [];

    /**
     * @param string|int    $shardId
     * @param Configuration $configuration
     */
    public function addShard($shardId, Configuration $configuration)
    {
        $this->shards[$shardId] = $configuration;
    }

    /**
     * @param int|string $shardId
     *
     * @return Configuration
     *
     * @throws InvalidShardIdException
     */
    public function getShard($shardId): Configuration
    {
        if (!isset($this->shards[$shardId])) {
            throw InvalidShardIdException::create($shardId);
        }

        return $this->shards[$shardId];
    }
}
