<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07;

use App\Advent\Domain\PuzzleSolver;

final readonly class Part1Solver implements PuzzleSolver
{

    public function year(): int { return 2015; }
    public function day(): int { return 7; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $wiremap = $this->inputSanitizer->sanitize($input);
        $wiremap->renderActions();

        return (string)$wiremap->getTotal();
    }
}
