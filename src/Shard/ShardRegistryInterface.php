<?php

namespace MDobak\DoctrineDynamicShardsBundle\Shard;

/**
 * Interface ShardRegistryInterface.
 *
 * @author Michał Dobaczewski <mdobak@gmail.com>
 */
interface ShardRegistryInterface
{
    /**
     * @param int $shardId
     *
     * @return array
     */
    public function getShard(int $shardId): array;
}
