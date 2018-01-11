<?php

declare(strict_types = 1);

namespace Gogart\Cli\EventStore;

use Prooph\EventStore\EventStore;
use Prooph\EventStore\Stream;
use Prooph\EventStore\StreamName;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

class CreateEventStreamCommand extends Command
{
    private const COMMAND_NAME = 'event-store:event-stream:create';

    /**
     * @var EventStore
     */
    private $eventStore;

    /**
     * @param EventStore $eventStore
     */
    public function __construct(EventStore $eventStore)
    {
        $this->eventStore = $eventStore;

        parent::__construct(self::COMMAND_NAME);
    }

    protected function configure()
    {
        $this->setName(self::COMMAND_NAME);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|null|void
     *
     * @throws ServiceNotFoundException
     * @throws ServiceCircularReferenceException
     * @throws \LogicException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $stream = new Stream(new StreamName('event_stream'), new \ArrayIterator([]));

        $this->eventStore->create($stream);

        $output->writeln('<info>Event was created</info>');
    }
}
