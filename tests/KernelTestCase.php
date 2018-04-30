<?php

namespace MDobak\DoctrineDynamicShardsBundle\Tests;

use PHPUnit\Framework\TestCase;

class KernelTestCase extends TestCase
{
    /**
     * @var AppKernel
     */
    protected static $kernel;

    public static function setUpBeforeClass()
    {
        require_once __DIR__.'/AppKernel.php';

        $kernel = new AppKernel('test', true);
        $kernel->boot();

        self::$kernel = $kernel;
    }

    public static function tearDownAfterClass()
    {
        $cacheDir = self::$kernel->getCacheDir();
        @unlink($cacheDir.'/0.db');
        @unlink($cacheDir.'/1.db');
        @unlink($cacheDir.'/2.db');
    }
}
