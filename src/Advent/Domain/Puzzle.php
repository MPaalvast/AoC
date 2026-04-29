<?php

declare(strict_types=1);

namespace App\Advent\Domain;

use App\Advent\Domain\ValueObject\Day;
use App\Advent\Domain\ValueObject\Part;
use App\Advent\Domain\ValueObject\Year;

final readonly class Puzzle
{
    public function __construct(
        private Year $year,
        private Day $day,
        private Part $part,
        private string $title,
        private string $description
    ) {
    }

    public function year(): Year
    {
        return $this->year;
    }

    public function day(): Day
    {
        return $this->day;
    }

    public function part(): Part
    {
        return $this->part;
    }

    public function title(): string
    {
        return $this->title;
    }

    public function description(): string
    {
        return $this->description;
    }
}
