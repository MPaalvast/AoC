<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Registry;

use App\Advent\Domain\PuzzleSolver;
use App\Advent\Domain\SolutionRegistry;

final readonly class PhpClassSolutionRegistry implements SolutionRegistry
{
    /**
     * @param iterable<PuzzleSolver> $solvers
     */
    public function __construct(private iterable $solvers)
    {
    }

    public function findSolver(int $year, int $day, int $part): ?PuzzleSolver
    {
        foreach ($this->solvers as $solver) {
            if ($solver->year() === $year && $solver->day() === $day && $solver->part() === $part) {
                return $solver;
            }
        }

        return null;
    }

    public function index(): array
    {
        $index = [];

        foreach ($this->solvers as $solver) {
            $index[$solver->year()][$solver->day()][] = $solver->part();
        }

        foreach ($index as &$days) {
            foreach ($days as &$parts) {
                $parts = array_values(array_unique($parts));
                sort($parts);
            }
            unset($parts);
            ksort($days);
        }
        unset($days);

        ksort($index);

        return $index;
    }
}
