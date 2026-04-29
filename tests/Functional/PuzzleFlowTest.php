<?php

declare(strict_types=1);

namespace App\Tests\Functional;

use App\Advent\Application\Command\RunSolution\RunSolutionCommand;
use App\Advent\Application\Command\RunSolution\RunSolutionHandler;
use App\Advent\Application\Query\GetPuzzle\GetPuzzleHandler;
use App\Advent\Application\Query\GetPuzzle\GetPuzzleQuery;
use App\Advent\Infrastructure\Puzzle\InMemoryPuzzleRepository;
use App\Advent\Infrastructure\Solution\Registry\PhpClassSolutionRegistry;
use App\Advent\Infrastructure\Solution\Year2025\Day01\Part1Solver;
use PHPUnit\Framework\TestCase;

final class PuzzleFlowTest extends TestCase
{
    public function testMenuSelectionDataExists(): void
    {
        $repository = new InMemoryPuzzleRepository();

        self::assertSame([2025], $repository->availableYears());
        self::assertSame([1], $repository->availableDays(2025));
        self::assertSame([1, 2], $repository->availableParts(2025, 1));
    }

    public function testGetPuzzleAndRunSolutionFlow(): void
    {
        $repository = new InMemoryPuzzleRepository();
        $registry = new PhpClassSolutionRegistry([new Part1Solver()]);
        $getPuzzle = new GetPuzzleHandler($repository);
        $runSolution = new RunSolutionHandler($registry);

        $puzzle = $getPuzzle(new GetPuzzleQuery(2025, 1, 1));
        self::assertNotNull($puzzle);
        self::assertSame('Calorie Calibration', $puzzle->title);

        $result = $runSolution(new RunSolutionCommand(2025, 1, 1, "1\n2\n3\n"));
        self::assertSame('6', $result->output);
    }
}
