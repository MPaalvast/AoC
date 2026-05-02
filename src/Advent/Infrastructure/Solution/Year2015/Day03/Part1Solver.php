<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day03;

use App\Advent\Domain\PuzzleSolver;

final class Part1Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 3; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $navigationMap = $this->inputSanitizer->sanitize($input);

        $totalPaper = $navigationMap->getHousesVisitedBySanta();

        return (string) $totalPaper;
    }
}
