<?php

declare(strict_types=1);

namespace App\Advent\Domain;

interface PuzzleRepository
{
    public function findPuzzle(int $year, int $day, int $part): ?Puzzle;

    /**
     * @return int[]
     */
    public function availableYears(): array;

    /**
     * @return int[]
     */
    public function availableDays(int $year): array;

    /**
     * @return int[]
     */
    public function availableParts(int $year, int $day): array;

    /**
     * @return array<int, array<int, string>>
     */
    public function dayProgresses(int $year): array;

    public function partStatus(int $year, int $day, int $part): ?string;

    public function markPartSolved(int $year, int $day, int $part): void;

    public function addYear(int $year, int $maxDays): void;

    public function addDay(int $year, int $day): void;
}
