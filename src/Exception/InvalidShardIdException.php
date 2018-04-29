<?php

namespace MDobak\DoctrineDynamicShardsBundle\Exception;

/**
 * Class InvalidShardIdException
 *
 * @author Michal Dobaczewski <mdobak@gmail.com>
 */
class InvalidShardIdException extends AbstractDoctrineDynamicShardsException
{
    /**
     * @param $shardId
     *
     * @return InvalidShardIdException
     */
    public static function create($shardId)
    {
        return new self(sprintf('Shard "%s" do not exists', $shardId));
    }
}
