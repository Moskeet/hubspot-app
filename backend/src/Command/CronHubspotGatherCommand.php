<?php

namespace App\Command;

use App\Entity\HubspotToken;
use App\Hubspot\HubspotManager;
use Doctrine\ORM\EntityManagerInterface;
use Leezy\PheanstalkBundle\Proxy\PheanstalkProxy;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CronHubspotGatherCommand extends Command
{
    protected static $defaultName = 'app:cron:hubspot-gather';

    /**
     * @var PheanstalkProxy
     */
    private $pheanstalk;

    /**
     * @var string
     */
    private $tubeName;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var HubspotManager
     */
    private $hubspotManager;

    /**
     * @param EntityManagerInterface $em
     * @param HubspotManager $hubspotManager
     */
    public function __construct(
        EntityManagerInterface $em,
        HubspotManager $hubspotManager
    ) {
        $this->em = $em;
        $this->hubspotManager = $hubspotManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Hubspot gather data by token.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $hubspotTokenRepository = $this->em->getRepository(HubspotToken::class);

        foreach ($hubspotTokenRepository->findAll() as $hubspotToken) {
            $this->hubspotManager->fetchContacts($hubspotToken);
        }
    }
}
