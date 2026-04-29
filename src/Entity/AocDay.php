<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'aoc_day')]
#[ORM\UniqueConstraint(name: 'uniq_aoc_day', columns: ['year_id', 'day_number'])]
class AocDay
{
    public const STATUS_NOT_STARTED = 'not_started';
    public const STATUS_SOLVED = 'solved';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: AocYear::class, inversedBy: 'days')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private AocYear $year;

    #[ORM\Column(name: 'day_number', type: 'integer')]
    private int $dayNumber;

    #[ORM\Column(name: 'part1_status', length: 20)]
    private string $part1Status = self::STATUS_NOT_STARTED;

    #[ORM\Column(name: 'part2_status', length: 20)]
    private string $part2Status = self::STATUS_NOT_STARTED;

    public function __construct(AocYear $year, int $dayNumber)
    {
        $this->year = $year;
        $this->dayNumber = $dayNumber;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getYear(): AocYear
    {
        return $this->year;
    }

    public function getDayNumber(): int
    {
        return $this->dayNumber;
    }

    public function getPartStatus(int $part): string
    {
        return $part === 1 ? $this->part1Status : $this->part2Status;
    }

    public function markSolved(int $part): void
    {
        if ($part === 1) {
            $this->part1Status = self::STATUS_SOLVED;

            return;
        }

        $this->part2Status = self::STATUS_SOLVED;
    }
}
