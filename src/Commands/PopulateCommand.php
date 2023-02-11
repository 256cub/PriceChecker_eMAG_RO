<?php

namespace App\Commands;

use App\Controller\PopulateController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class PopulateCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('populate')
            ->setDescription('Start populating with data from website eMag.ro')
            ->setHelp('Parsing data from website eMag.ro.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $helper = $this->getHelper('question');

        $question = new Question("<info> Enter keyword to search for: </info>", false);
        $searchTerm = $helper->ask($input, $output, $question);

        $question = new Question("<comment> Max limit to parse: </comment>", 100);
        $limit = $helper->ask($input, $output, $question);

        $text = ' Search <info>"' . $limit . '"</info> products by keyword <comment>"' . $searchTerm . '"</comment>';
        $output->writeln($text);

        $timeStart = microtime(true);

        $controller = new PopulateController($this->entityManager);
        $response = $controller->populate($limit, $searchTerm);

        $output->writeln($response->getContent());
        $output->writeln('<info>Total Execution Time: </info>' . microtime(true) - $timeStart);

        return Command::SUCCESS;
    }

}
