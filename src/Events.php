<?php

namespace MDobak\DoctrineDynamicShardsBundle;

/**
 * Class Events.
 *
 * @author Michał Dobaczewski <mdobak@gmail.com>
 */
final class Events
{
    /**
     * Private constructor. This class cannot be instantiated.
     */
    private function __construct()
    {
    }

    const DYNAMIC_SHARD_PRE_CONNECT  = 'mdobak.dynamic_shard_pre_connect';
    const DYNAMIC_SHARD_POST_CONNECT = 'mdobak.dynamic_shard_post_connect';
}
