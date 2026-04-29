<?php

declare(strict_types=1);

namespace App\Advent\Application\Query\GetPuzzle;

final readonly class PuzzleView
{
    public function __construct(
        public int $year,
        public int $day,
        public int $part,
        public string $title,
        public string $description
    ) {
    }
}
