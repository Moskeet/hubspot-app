<?php

namespace App\Command;

use App\Entity\HubspotToken;
use App\Hubspot\HubspotManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class TokenRefreshCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'app:token:refresh';

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
            ->setDescription('Add a short description for your command')
            ->addOption('diff', null, InputOption::VALUE_OPTIONAL, 'Define seconds before expiring token, to be processed.', 300)
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

        if (!$input->getOption('diff')) {
            $io->error('--diff should be defined');
        }

        $timeToCheck = new \DateTime('+' . $input->getOption('diff') . ' seconds');
        $hubspotTokenRepository = $this->em->getRepository(HubspotToken::class);

        foreach ($hubspotTokenRepository->getAllAfterMarker($timeToCheck) as $hubspotToken) {
            if (!$this->hubspotManager->refreshToken($hubspotToken)) {
                $io->error(sprintf('Token "%d" was not updated.', $hubspotToken->getId()));

                continue;
            }

            $this->em->persist($hubspotToken);
            $this->em->flush();
        }
    }
}
