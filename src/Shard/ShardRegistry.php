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
     * @param       $shardId
     * @param array $params
     */
    public function addShard($shardId, array $params)
    {
        $this->shards[$shardId] = $params;
    }

    /**
     * @param int|string $shardId
     *
     * @return array
     * @throws InvalidShardIdException
     */
    public function getShard($shardId): array
    {
        if (!isset($this->shards[$shardId])) {
            throw InvalidShardIdException::create($shardId);
        }

        return $this->shards[$shardId];
    }
}
