<?php

declare(strict_types=1);

namespace App\Advent\Application\Query\GetPuzzle;

use App\Advent\Domain\PuzzleRepository;

final readonly class GetPuzzleHandler
{
    public function __construct(private PuzzleRepository $puzzleRepository)
    {
    }

    public function __invoke(GetPuzzleQuery $query): ?PuzzleView
    {
        $puzzle = $this->puzzleRepository->findPuzzle($query->year, $query->day, $query->part);

        if ($puzzle === null) {
            return null;
        }

        return new PuzzleView(
            year: $puzzle->year()->value(),
            day: $puzzle->day()->value(),
            part: $puzzle->part()->value(),
            title: $puzzle->title(),
            description: $puzzle->description()
        );
    }
}
