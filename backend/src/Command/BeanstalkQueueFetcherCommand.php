<?php

namespace App\Command;

use App\Queue\HubspotTokenQueue;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BeanstalkQueueFetcherCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:beanstalk:queue-hubspot-fetcher';

    /**
     * @var HubspotTokenQueue
     */
    private $hubspotTokenQueue;

    /**
     * @param HubspotTokenQueue $hubspotTokenQueue
     */
    public function __construct(
        HubspotTokenQueue $hubspotTokenQueue
    ) {
        $this->hubspotTokenQueue = $hubspotTokenQueue;
        parent::__construct();
    }

    protected function configure(

    ) {
        $this
            ->setDescription('Beanstalk continuous loop, to listen to queues.');
        ;
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return int|void|null
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->hubspotTokenQueue->listen();
    }
}
