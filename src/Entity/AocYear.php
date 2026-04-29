<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'aoc_year')]
#[ORM\UniqueConstraint(name: 'uniq_aoc_year_year_number', columns: ['year_number'])]
class AocYear
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'year_number', type: 'integer')]
    private int $yearNumber;

    #[ORM\Column(name: 'max_days', type: 'integer')]
    private int $maxDays = 25;

    /**
     * @var Collection<int, AocDay>
     */
    #[ORM\OneToMany(mappedBy: 'year', targetEntity: AocDay::class, orphanRemoval: true)]
    private Collection $days;

    public function __construct(int $yearNumber, int $maxDays)
    {
        $this->yearNumber = $yearNumber;
        $this->maxDays = $maxDays;
        $this->days = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYearNumber(): int
    {
        return $this->yearNumber;
    }

    public function getMaxDays(): int
    {
        return $this->maxDays;
    }
}
