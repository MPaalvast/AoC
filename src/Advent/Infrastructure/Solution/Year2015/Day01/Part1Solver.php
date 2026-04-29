<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day01;

use App\Advent\Domain\PuzzleSolver;

final readonly class Part1Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 1; }
    public function part(): int { return 1; }

    public function __construct(
        private InputValidator $inputValidator,
        private InputSanitizer $inputSanitizer
    ) {}

    /**
     * Count all ( and ) values and subtract them from each-other.
     */
    public function solve(string $input): string
    {
        $this->inputValidator->validate($input);
        $sanitizedInput = $this->inputSanitizer->sanitize($input);
        $counts = array_count_values($sanitizedInput);
        $total = ($counts['('] ?? 0) - ($counts[')'] ?? 0);

        return (string) $total;
    }
}
