<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use League\Period\Period;

/**
 * Class Shift.
 *
 * @ORM\Entity
 */
final class Shift
{
    /**
     * @var int
     * @ORM\Column(type="integer")
     * @ORM\Id()
     * @ORM\GeneratedValue()
     */
    private $id;

    /**
     * @var Period
     *
     * @ORM\Column(type="daterange")
     */
    private $period;

    /**
     * Shift constructor.
     *
     * @param Period $period
     */
    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Period
     */
    public function getPeriod(): Period
    {
        return $this->period;
    }
}
