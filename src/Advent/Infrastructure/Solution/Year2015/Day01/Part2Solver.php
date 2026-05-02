<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day01;

use App\Advent\Domain\PuzzleSolver;

final class Part2Solver implements PuzzleSolver
{
    public function year(): int { return 2015; }
    public function day(): int { return 1; }
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
        $sanitizedInput = $this->inputSanitizer->sanitize($input);
        $i = 1;
        $level = 0;
        foreach ($sanitizedInput as $direction) {
            if ($direction === '(') {
                $level++;
            } else if ($direction === ')') {
                $level--;
            }
            if ($level === -1) {
                return (string) $i;
            }
            $i++;
        }

        return "Basement not reached!";
    }
}
