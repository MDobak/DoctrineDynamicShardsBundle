<?php

namespace MDobak\DoctrineDynamicShardsBundle;

use MDobak\DoctrineDynamicShardsBundle\DependencyInjection\Compiler\ConnectionFactoryCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class MDobakDoctrineDynamicShardsBundle.
 *
 * @author Michał Dobaczewski <mdobak@gmail.com>
 */
class MDobakDoctrineDynamicShardsBundle extends Bundle
{
	/**
	 * {@inheritdoc}
	 */
	public function build(ContainerBuilder $container)
	{
		parent::build($container);

		$container->addCompilerPass(new ConnectionFactoryCompilerPass());
	}

	/**
	 * {@inheritdoc}
	 */
	public function getContainerExtension()
	{
		if (null === $this->extension) {
			$extension = $this->createContainerExtension();

			if (null !== $extension) {
				if (!$extension instanceof ExtensionInterface) {
					throw new \LogicException(sprintf('Extension %s must implement Symfony\Component\DependencyInjection\Extension\ExtensionInterface.', get_class($extension)));
				}

				$this->extension = $extension;
			} else {
				$this->extension = false;
			}
		}

		if ($this->extension) {
			return $this->extension;
		}
	}
}
