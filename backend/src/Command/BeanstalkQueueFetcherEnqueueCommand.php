<?php

namespace App\Command;

use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BeanstalkQueueFetcherEnqueueCommand extends Command
{
    protected static $defaultName = 'app:beanstalk:queue-fetcher-enqueue';

    /**
     * @var PheanstalkProxy
     */
    private $pheanstalk;

    /**
     * @var string
     */
    private $tubeName;

    /**
     * @param PheanstalkProxy $pheanstalk
     * @param string $tubeName
     */
    public function __construct(
        PheanstalkProxy $pheanstalk,
        string $tubeName
    ) {
        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $tubeName;
        parent::__construct();
    }

    protected function configure(

    ) {
        $this
            ->setDescription('Beanstalk continuous loop, to listen to queues.');
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $job = $this->pheanstalk
            ->useTube($this->tubeName)
            ->put(mt_rand(1000, 9999))
        ;
    }
}
