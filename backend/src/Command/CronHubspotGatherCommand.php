<?php

namespace App\Command;

use App\Entity\HubspotToken;
use App\Hubspot\HubspotManager;
use App\WickedReports\WickerReportManager;
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
     * @var WickerReportManager
     */
    private $wickerReportManager;

    /**
     * @param EntityManagerInterface $em
     * @param PheanstalkProxy $pheanstalk
     * @param string $tubeName
     * @param HubspotManager $hubspotManager
     * @param WickerReportManager $wickerReportManager
     */
    public function __construct(
        EntityManagerInterface $em,
        PheanstalkProxy $pheanstalk,
        string $tubeName,
        HubspotManager $hubspotManager,
        WickerReportManager $wickerReportManager
    ) {
        $this->em = $em;
        $this->pheanstalk = $pheanstalk;
        $this->tubeName = $tubeName;
        $this->hubspotManager = $hubspotManager;
        $this->wickerReportManager = $wickerReportManager;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Hubspot gather data by token.')
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
        $hubspotTokenRepository = $this->em->getRepository(HubspotToken::class);

        foreach ($hubspotTokenRepository->findAll() as $hubspotToken) {
            $wickedReportContacts = $this->hubspotManager->fetchContacts($hubspotToken);

            if ($wickedReportContacts === null) {
                continue;
            }

            if (!$this->wickerReportManager->storeContacts($wickedReportContacts)) {
                continue;
            }

            $hubspotToken->setTimeOffset($wickedReportContacts->getTimeOffset());
            $this->em->persist($hubspotToken);
            $this->em->flush();

            if (!$wickedReportContacts->getHasMore()) {
                continue;
            }

            $this->pheanstalk
                ->useTube($this->tubeName)
                ->put($hubspotToken->getId())
            ;
        }
    }
}
