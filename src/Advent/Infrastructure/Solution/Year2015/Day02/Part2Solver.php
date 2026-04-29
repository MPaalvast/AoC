<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day02;

use App\Advent\Domain\PuzzleSolver;
use App\Advent\Infrastructure\Solution\Year2015\Day02\ValueObject\Package;

final class Part2Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 2; }
    public function part(): int { return 2; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    /**
     * Walk over the array and fond the first time you reach the basement (level: -1)
     */
    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $packages = $this->inputSanitizer->sanitize($input);

        $total = array_sum(
            array_map(
                fn(Package $package) => $package->ribbonLength() + $package->bowSize(),
                $packages
            )
        );

        return (string) $total;
    }
}
