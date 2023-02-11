<?php

namespace App\Commands;

use App\Controller\ParseController;
use App\Entity\Process;
use App\Model\Constants\Message;
use App\Model\Constants\ProcessProductStatus;
use App\Model\Constants\ProcessStatus;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ParserCommand extends Command
{

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('parse:all')
            ->setDescription('Start parsing the website eMag.ro')
            ->setHelp('Parsing the website eMag.ro.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

        $timeStart = microtime(true);

        $em = $this->entityManager;

        $response = [];

        $process = new Process();
        $process->setStatus(ProcessStatus::RUNNING);

        $em->persist($process);
        $em->flush();

        $products = $em->getRepository('App:Products')->findAll();
        if ($products) {

            $controller = new ParseController($this->entityManager);

            foreach ($products as $product) {
                $data = json_decode($controller->parseSingle($product->getId(), $process)->getContent(), true, 512, JSON_THROW_ON_ERROR);

                switch ($data['status']) {
                    case ProcessProductStatus::PRICE_UPDATED:
                        $output->writeln('<info>' . json_encode($data) . '</info>');
                        break;
                    case ProcessProductStatus::ERROR:
                        $output->writeln('<error>' . json_encode($data) . '</error>');
                        break;
                    case ProcessProductStatus::PRICE_SHOULD_BE_UPDATED:
                        $output->writeln('<question>' . json_encode($data) . '</question>');
                        break;
                    default:
                        $output->writeln(json_encode($data));
                }

                $response[] = $data;
            }

            $process->setReport($response);
            $process->setStatus(ProcessStatus::DONE);

            $em->persist($process);
            $em->flush();

            $output->writeln('<info>Total Execution Time: </info>' . microtime(true) - $timeStart);

            return Command::SUCCESS;
        }

        $process->setStatus(ProcessStatus::ERROR);
        $process->setReport([Message::PRODUCT_NOT_FOUND]);

        $em->persist($process);
        $em->flush();

        $output->writeln('<info>Total Execution Time: </info>' . microtime(true) - $timeStart);

        return Command::FAILURE;
    }

}
