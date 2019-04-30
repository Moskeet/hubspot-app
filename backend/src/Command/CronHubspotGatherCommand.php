<?php

namespace App\Command;

use App\Entity\HubspotToken;
use App\Queue\HubspotTokenQueue;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CronHubspotGatherCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:cron:hubspot-gather';

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var HubspotTokenQueue
     */
    private $hubspotTokenQueue;

    /**
     * @param EntityManagerInterface $em
     * @param HubspotTokenQueue $hubspotTokenQueue
     */
    public function __construct(
        EntityManagerInterface $em,
        HubspotTokenQueue $hubspotTokenQueue
    ) {
        $this->em = $em;
        $this->hubspotTokenQueue = $hubspotTokenQueue;
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
            $this->hubspotTokenQueue->serveToken($hubspotToken);
        }
    }
}
