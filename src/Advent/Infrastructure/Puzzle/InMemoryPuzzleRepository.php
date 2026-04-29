<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Puzzle;

use App\Advent\Domain\Puzzle;
use App\Advent\Domain\PuzzleRepository;
use App\Advent\Domain\ValueObject\Day;
use App\Advent\Domain\ValueObject\Part;
use App\Advent\Domain\ValueObject\Year;

final class InMemoryPuzzleRepository implements PuzzleRepository
{
    /**
     * @var array<int, array<int, array<int, Puzzle>>>
     */
    private array $puzzles;
    /**
     * @var array<int, array<int, array<int, string>>>
     */
    private array $progress = [];

    public function __construct()
    {
        $sample = new Puzzle(
            new Year(2025),
            new Day(1),
            new Part(1),
            'Calorie Calibration',
            'Given lines with integer values, return the sum of all numbers.'
        );

        $this->puzzles = [
            2025 => [
                1 => [
                    1 => $sample,
                    2 => new Puzzle(
                        new Year(2025),
                        new Day(1),
                        new Part(2),
                        'Calorie Calibration Part 2',
                        'Second part placeholder.'
                    ),
                ],
            ],
        ];
        $this->progress = [
            2025 => [
                1 => [
                    1 => 'not_started',
                    2 => 'not_started',
                ],
            ],
        ];
    }

    public function findPuzzle(int $year, int $day, int $part): ?Puzzle
    {
        return $this->puzzles[$year][$day][$part] ?? null;
    }

    public function availableYears(): array
    {
        $years = array_keys($this->puzzles);
        sort($years);

        return $years;
    }

    public function availableDays(int $year): array
    {
        $days = array_keys($this->puzzles[$year] ?? []);
        sort($days);

        return $days;
    }

    public function availableParts(int $year, int $day): array
    {
        $parts = array_keys($this->puzzles[$year][$day] ?? []);
        sort($parts);

        return $parts;
    }

    public function dayProgresses(int $year): array
    {
        return $this->progress[$year] ?? [];
    }

    public function partStatus(int $year, int $day, int $part): ?string
    {
        return $this->progress[$year][$day][$part] ?? null;
    }

    public function markPartSolved(int $year, int $day, int $part): void
    {
        if (!isset($this->progress[$year][$day][$part])) {
            return;
        }

        $this->progress[$year][$day][$part] = 'solved';
    }

    public function addYear(int $year, int $maxDays): void
    {
        if (isset($this->puzzles[$year])) {
            return;
        }

        $this->puzzles[$year] = [];
        $this->progress[$year] = [];
    }

    public function addDay(int $year, int $day): void
    {
        if (!isset($this->puzzles[$year])) {
            return;
        }

        if (isset($this->puzzles[$year][$day])) {
            return;
        }

        $this->puzzles[$year][$day] = [
            1 => new Puzzle(new Year($year), new Day($day), new Part(1), sprintf('Year %d Day %d Part 1', $year, $day), 'Puzzle details can be added later.'),
            2 => new Puzzle(new Year($year), new Day($day), new Part(2), sprintf('Year %d Day %d Part 2', $year, $day), 'Puzzle details can be added later.'),
        ];
        $this->progress[$year][$day] = [1 => 'not_started', 2 => 'not_started'];
    }
}
