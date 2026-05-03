<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day04;

use App\Advent\Domain\PuzzleSolver;
use App\Advent\Infrastructure\Solution\Year2015\Day04\ValueObject\PuzzleInput;

final readonly class Part1Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 4; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $puzzleInput = new PuzzleInput(
            $input,
            $this->inputValidator,
            $this->inputSanitizer
        );
        $MD5Finder = $puzzleInput->getFinder();

        $totalPaper = $MD5Finder->findKey("00000" );

        return (string) $totalPaper;
    }
}
