<?php

declare(strict_types=1);

namespace App\Advent\Application\Command\RunSolution;

use App\Advent\Domain\SolutionRegistry;

final readonly class RunSolutionHandler
{
    public function __construct(private SolutionRegistry $solutionRegistry)
    {
    }

    public function __invoke(RunSolutionCommand $command): RunSolutionResult
    {
        $solver = $this->solutionRegistry->findSolver($command->year, $command->day, $command->part);

        if ($solver === null) {
            throw new \RuntimeException('No solver registered for this puzzle.');
        }

        return new RunSolutionResult(
            year: $command->year,
            day: $command->day,
            part: $command->part,
            solverClass: $solver::class,
            output: $solver->solve($command->input)
        );
    }
}
