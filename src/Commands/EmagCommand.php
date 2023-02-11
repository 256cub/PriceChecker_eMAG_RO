<?php

namespace App\Commands;

use App\Controller\EmagController;
use App\Controller\PopulateController;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;


class EmagCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName('emag:update')
            ->setDescription('Start updating price using eMag.ro API')
            ->setHelp('Updating data from website eMag.ro using API');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $timeStart = microtime(true);

        $controller = new EmagController($this->entityManager);
        $data = json_decode($controller->update()->getContent(), true, 512, JSON_THROW_ON_ERROR);

        foreach ($data as $value) {
            $output->writeln(json_encode($value));
        }

        $output->writeln('<info>Total Execution Time: </info>' . microtime(true) - $timeStart);

        return Command::SUCCESS;
    }

}
