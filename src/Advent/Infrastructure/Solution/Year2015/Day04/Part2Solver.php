<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day04;

use App\Advent\Domain\PuzzleSolver;
use App\Advent\Infrastructure\Solution\Year2015\Day04\ValueObject\PuzzleInput;

final readonly class Part2Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 4; }
    public function part(): int { return 2; }

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

        $totalPaper = $MD5Finder->findKey("000000" );

        return (string) $totalPaper;
    }
}
