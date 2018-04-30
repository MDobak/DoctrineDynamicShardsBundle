<?php

namespace MDobak\DoctrineDynamicShardsBundle\Tests;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle;
use MDobak\DoctrineDynamicShardsBundle\MDobakDoctrineDynamicShardsBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [];
        $bundles[] = new FrameworkBundle();
        $bundles[] = new DoctrineBundle();
        $bundles[] = new DoctrineCacheBundle();
        $bundles[] = new MDobakDoctrineDynamicShardsBundle();

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config.yml');
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir()
    {
        return '/tmp/MDobakDoctrineDynamicShardsBundle/cache';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir()
    {
        return '/tmp/MDobakDoctrineDynamicShardsBundle/logs';
    }
}
