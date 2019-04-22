<?php

namespace App\Command;

use App\Entity\HubspotToken;
use App\Hubspot\HubspotManager;
use App\Repository\HubspotTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BeanstalkQueueFetcherCommand extends Command
{
    protected static $defaultName = 'app:beanstalk:queue-hubspot-fetcher';

    /**
     * @var PheanstalkProxy
     */
    private $pheanstalk;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var HubspotTokenRepository
     */
    private $hubspotTokenRepository;

    /**
     * @var HubspotManager
     */
    private $hubspotManager;

    /**
     * @var string
     */
    private $tubeName;

    /**
     * @param PheanstalkProxy $pheanstalk
     * @param EntityManagerInterface $em
     * @param HubspotManager $hubspotManager
     * @param string $tubeName
     */
    public function __construct(
        PheanstalkProxy $pheanstalk,
        EntityManagerInterface $em,
        HubspotManager $hubspotManager,
        string $tubeName
    ) {
        $this->pheanstalk = $pheanstalk;
        $this->em = $em;
        $this->hubspotManager = $hubspotManager;
        $this->tubeName = $tubeName;
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
        ini_set('default_socket_timeout', 86400);
        $io = new SymfonyStyle($input, $output);

        while (true) {
            $job = $this->pheanstalk
                ->watch($this->tubeName)
                ->ignore('default')
                ->reserve()
            ;
            $hubspotTokenId = $job->getData();
            $this->serve($hubspotTokenId);
            $this->pheanstalk->delete($job);
        }
    }

    /**
     * @param $hubspotTokenId
     */
    private function serve($hubspotTokenId)
    {
        $hubspotToken = $this->em->getRepository(HubspotToken::class)->find($hubspotTokenId);
        $this->hubspotManager->fetchContacts($hubspotToken);
    }
}
