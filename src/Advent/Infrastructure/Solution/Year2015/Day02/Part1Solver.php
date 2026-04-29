<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day02;

use App\Advent\Domain\PuzzleSolver;
use App\Advent\Infrastructure\Solution\Year2015\Day02\ValueObject\Package;

final class Part1Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 2; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $packages = $this->inputSanitizer->sanitize($input);

        $totalPaper = array_sum(
            array_map(
                fn(Package $p) => $p->surfaceArea(),
                $packages
            )
        );

        return (string) $totalPaper;
    }
}
