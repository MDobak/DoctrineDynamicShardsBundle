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
     * @param string|int $shardId
     *
     * @return Configuration
     */
    public function getShard($shardId): Configuration;
}
