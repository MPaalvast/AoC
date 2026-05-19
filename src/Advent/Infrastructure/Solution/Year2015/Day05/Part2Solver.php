<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day05;

use App\Advent\Domain\PuzzleSolver;

final readonly class Part2Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 5; }
    public function part(): int { return 2; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        return $this->inputSanitizer->sanitize($input)->getNiceStringsPart2();
    }
}
