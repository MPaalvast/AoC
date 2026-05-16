<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day06;

use App\Advent\Domain\PuzzleSolver;

final readonly class Part1Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 6; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $lightGrid = $this->inputSanitizer->sanitize($input);
        $lightGrid->checkLitLights();

        return $lightGrid->getLit();
    }
}
