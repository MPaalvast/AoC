<?php

declare(strict_types=1);

namespace App\Advent\Application\Query\GetPuzzle;

final readonly class GetPuzzleQuery
{
    public function __construct(
        public int $year,
        public int $day,
        public int $part
    ) {
    }
}
