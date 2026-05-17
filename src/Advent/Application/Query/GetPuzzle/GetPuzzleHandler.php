<?php

declare(strict_types=1);

namespace App\Advent\Application\Query\GetPuzzle;

use App\Advent\Domain\SolutionRegistry;

final readonly class GetPuzzleHandler
{
    public function __construct(private SolutionRegistry $solutionRegistry)
    {
    }

    public function __invoke(GetPuzzleQuery $query): ?PuzzleView
    {
        $solver = $this->solutionRegistry->findSolver($query->year, $query->day, $query->part);
        if ($solver === null) {
            return null;
        }

        return new PuzzleView(
            year: $query->year,
            day: $query->day,
            part: $query->part,
            title: sprintf('Year %d Day %02d', $query->year, $query->day),
            description: sprintf('Solver beschikbaar voor Year %d Day %02d Part %d.', $query->year, $query->day, $query->part)
        );
    }
}
