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
     * @var array[]
     */
    protected $shards = [];

    /**
     * @param int   $shardId
     * @param array $params
     */
    public function addShard(int $shardId, array $params)
    {
        $this->shards[$shardId] = $params;
    }

    /**
     * @param int $shardId
     *
     * @return array
     * @throws InvalidShardIdException
     */
    public function getShard(int $shardId): array
    {
        if (!isset($this->shards[$shardId])) {
            throw InvalidShardIdException::create($shardId);
        }

        return $this->shards[$shardId];
    }
}
