<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day08;

use App\Advent\Domain\PuzzleSolver;

class Part1Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 8; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $stringCounter = $this->inputSanitizer->sanitize($input);

        return $stringCounter->calculateLiteralMinusActual();
    }
}
