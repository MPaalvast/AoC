<?php

declare(strict_types=1);

namespace App\Advent\Application\Command\RunSolution;

final readonly class RunSolutionResult
{
    public function __construct(
        public int $year,
        public int $day,
        public int $part,
        public string $solverClass,
        public string $output
    ) {
    }
}
