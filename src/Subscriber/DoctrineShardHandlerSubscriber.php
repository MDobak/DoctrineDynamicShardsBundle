<?php

namespace MDobak\DoctrineDynamicShardsBundle\Subscriber;

use Doctrine\DBAL\Connection;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class DoctrineShardHandlerSubscriber
 */
class DoctrineShardHandlerSubscriber implements EventSubscriberInterface, ContainerAwareInterface
{
    use ContainerAwareTrait;

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::COMMAND => [
                ['handleShardArgument'],
            ],
        ];
    }

    /**
     * @param ConsoleCommandEvent $event
     */
    public function handleShardArgument(ConsoleCommandEvent $event)
    {
        $inputDefinition = $event->getCommand()->getDefinition();

        $inputDefinition->addOption(
            new InputOption(
                'dbal-shard',
                null,
                InputOption::VALUE_OPTIONAL,
                'The shard ID to use with this command',
                null
            )
        );

        $inputDefinition->addOption(
            new InputOption(
                'dbal-connection',
                null,
                InputOption::VALUE_REQUIRED,
                'The DBAL connection name to use with this command',
                null
            )
        );

        // Due to Symfony bug there is no better solution to handle these parameters:
        // https://github.com/symfony/symfony/issues/19441
        $input = new ArgvInput();
        $input->bind($inputDefinition);

        if ($input->getOption('dbal-shard') || $input->getOption('dbal-connection')) {
            $shard =          $input->getOption('dbal-shard');
            $connectionName = $input->getOption('dbal-connection') ?? 'default';

            /** @var Connection $connection */
            $connection = $this->container->get(sprintf('doctrine.dbal.%s_connection', $connectionName));
            $connection->connect($shard);
        }
    }
}
