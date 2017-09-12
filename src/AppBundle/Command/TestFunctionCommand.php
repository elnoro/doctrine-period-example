<?php

namespace AppBundle\Command;

use AppBundle\Doctrine\Type\DateRangeType;
use AppBundle\Entity\Shift;
use Doctrine\DBAL\Types\Type;
use League\Period\Period;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestFunctionCommand extends ContainerAwareCommand
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('app:test_function_command')
            ->setDescription('Hello PhpStorm');
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        // create a shift for the first quarter of 2000 - we'll use it to check overlap
        $period = Period::createFromQuarter(2000, 1);
        $shift = new Shift($period);

        $entityManager = $this->getContainer()->get('doctrine.orm.entity_manager');

        $entityManager->persist($shift);
        $entityManager->flush($shift);

        $repository = $entityManager->getRepository(Shift::class);

        // write a query
        $query = $repository->createQueryBuilder('shift')
            // use a function we've just written
            ->where('overlaps(shift.period, :new_shift_period) = true')
            ->setParameter(
                'new_shift_period',
                Period::createFromMonth(2000, 2), // February, 2000
                // pass type to tell doctrine how this value should be represented in SQL
                DateRangeType::DATE_RANGE_TYPE
            )
            ->getQuery();

        /** @var Shift[] $results */
        $results = $query->getResult();

        foreach ($results as $result) {
            $output->writeln(sprintf('New period overlaps with %s', $result->getPeriod()));
        }
    }
}
