<?php

namespace MDobak\DoctrineDynamicShardsBundle\Tests;

use MDobak\DoctrineDynamicShardsBundle\Doctrine\DBAL\DynamicShardConnection;

class SwitchShardTest extends KernelTestCase
{
    public function testDefaultConnection()
    {
        /** @var DynamicShardConnection $connection */
        $connection = self::$kernel->getContainer()->get('doctrine.dbal.default_connection');

        $connection->query('CREATE TABLE test(id INTEGER PRIMARY KEY);');
        $connection->query('INSERT INTO test(id) VALUES (0)');
        $result = $connection->query('SELECT * FROM test');

        $this->assertEquals(
            [['id' => 0]],
            $result->fetchAll()
        );
    }

    public function testReconnect()
    {
        /** @var DynamicShardConnection $connection */
        $connection = self::$kernel->getContainer()->get('doctrine.dbal.default_connection');
        $connection->connect(0);

        $result = $connection->query('SELECT * FROM test');

        $this->assertEquals(
            [['id' => 0]],
            $result->fetchAll()
        );
    }

    public function testShard()
    {
        /** @var DynamicShardConnection $connection */
        $connection = self::$kernel->getContainer()->get('doctrine.dbal.default_connection');
        $connection->connect(1);

        $connection->query('CREATE TABLE test(id INTEGER PRIMARY KEY);');
        $connection->query('INSERT INTO test(id) VALUES (0)');
        $result = $connection->query('SELECT * FROM test');

        $this->assertEquals(
            [['id' => 0]],
            $result->fetchAll()
        );
    }
}
