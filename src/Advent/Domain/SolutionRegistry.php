<?php

declare(strict_types=1);

namespace App\Advent\Domain;

interface SolutionRegistry
{
    public function findSolver(int $year, int $day, int $part): ?PuzzleSolver;

    /**
     * @return array<int, array<int, int[]>>
     */
    public function index(): array;
}
