<?php

namespace AppBundle\Command;

use AppBundle\Entity\Shift;
use League\Period\Period;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:test_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $period = Period::createFromMonth(2017, 1);
        $shift = new Shift($period);

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->persist($shift);
        $entityManager->flush($shift);

        $repository = $entityManager->getRepository(Shift::class);

        /** @var Shift $shift */
        $shift = $repository->findOneBy([], ['id' => 'DESC']);

        $output->writeln(sprintf('Shift is %s', $shift->getPeriod()));
    }
}
