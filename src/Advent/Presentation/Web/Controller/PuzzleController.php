<?php

declare(strict_types=1);

namespace App\Advent\Presentation\Web\Controller;

use App\Advent\Application\Command\RunSolution\RunSolutionCommand;
use App\Advent\Application\Command\RunSolution\RunSolutionHandler;
use App\Advent\Application\Query\GetPuzzle\GetPuzzleHandler;
use App\Advent\Application\Query\GetPuzzle\GetPuzzleQuery;
use App\Advent\Domain\SolutionRegistry;
use App\Advent\Infrastructure\Progress\JsonSolvedPartStore;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PuzzleController extends AbstractController
{
    public function __construct(private readonly JsonSolvedPartStore $solvedPartStore)
    {
    }
    #[Route('/', name: 'aoc_home', methods: ['GET'])]
    public function home(SolutionRegistry $solutionRegistry): Response
    {
        $index = $solutionRegistry->index();

        return $this->render('puzzle/menu.html.twig', [
            'years' => array_keys($index),
            'days' => [],
            'parts' => [],
            'dayProgresses' => [],
            'partStatuses' => [],
            'selectedYear' => null,
            'selectedDay' => null,
            'selectedPart' => null,
        ]);
    }

    #[Route('/{year}', name: 'aoc_year', requirements: ['year' => '\d{4}'], methods: ['GET'])]
    public function year(int $year, SolutionRegistry $solutionRegistry): Response
    {
        $index = $solutionRegistry->index();

        return $this->render('puzzle/menu.html.twig', [
            'years' => array_keys($index),
            'days' => $this->daysForYear($index, $year),
            'parts' => [],
            'dayProgresses' => $this->buildDayProgresses($index, $year),
            'partStatuses' => [],
            'selectedYear' => $year,
            'selectedDay' => null,
            'selectedPart' => null,
        ]);
    }

    #[Route('/{year}/{day}', name: 'aoc_day', requirements: ['year' => '\d{4}', 'day' => '\d{1,2}'], methods: ['GET'])]
    public function day(int $year, int $day, SolutionRegistry $solutionRegistry): Response
    {
        $index = $solutionRegistry->index();

        return $this->render('puzzle/menu.html.twig', [
            'years' => array_keys($index),
            'days' => $this->daysForYear($index, $year),
            'parts' => $this->partsForDay($index, $year, $day),
            'dayProgresses' => $this->buildDayProgresses($index, $year),
            'partStatuses' => $this->buildPartStatuses($index, $year, $day),
            'selectedYear' => $year,
            'selectedDay' => $day,
            'selectedPart' => null,
        ]);
    }

    #[Route('/{year}/{day}/{part}', name: 'aoc_part', requirements: ['year' => '\d{4}', 'day' => '\d{1,2}', 'part' => '[1-2]'], methods: ['GET', 'POST'])]
    public function part(
        int $year,
        int $day,
        int $part,
        Request $request,
        SolutionRegistry $solutionRegistry,
        GetPuzzleHandler $getPuzzleHandler,
        RunSolutionHandler $runSolutionHandler
    ): Response {
        $index = $solutionRegistry->index();
        $days = $this->daysForYear($index, $year);
        $parts = $this->partsForDay($index, $year, $day);

        if ($days === [] || !in_array($day, $days, true) || !in_array($part, $parts, true)) {
            throw $this->createNotFoundException('Puzzle not found.');
        }

        $part1Puzzle = $getPuzzleHandler(new GetPuzzleQuery($year, $day, 1));
        $part2Puzzle = $getPuzzleHandler(new GetPuzzleQuery($year, $day, 2));
        if ($part1Puzzle === null || $part2Puzzle === null) {
            throw $this->createNotFoundException('Puzzle not found.');
        }

        $input = '';
        $results = [
            1 => null,
            2 => null,
        ];
        $errorMessage = null;
        if ($request->isMethod('POST')) {
            $input = (string) $request->request->get('input', '');
            $result1Output = trim((string) $request->request->get('result_1_output', ''));
            $result2Output = trim((string) $request->request->get('result_2_output', ''));
            $results = [
                1 => $result1Output === '' ? null : ['output' => $result1Output],
                2 => $result2Output === '' ? null : ['output' => $result2Output],
            ];

            try {
                $results[$part] = $runSolutionHandler(new RunSolutionCommand($year, $day, $part, $input));
            } catch (\RuntimeException $exception) {
                $errorMessage = $exception->getMessage();
                if (!$request->headers->has('Turbo-Frame')) {
                    $this->addFlash('error', $errorMessage);
                }
            }

            $frameId = $request->headers->get('Turbo-Frame');
            if ($frameId === sprintf('part-result-%d', $part)) {
                return $this->render('puzzle/_part_result.html.twig', [
                    'frameId' => $frameId,
                    'result' => $results[$part],
                    'errorMessage' => $errorMessage,
                    'emptyText' => sprintf('Nog geen resultaat voor part %d.', $part),
                ]);
            }
        }

        return $this->render('puzzle/run.html.twig', [
            'years' => array_keys($index),
            'days' => $days,
            'parts' => $parts,
            'dayProgresses' => $this->buildDayProgresses($index, $year),
            'partStatuses' => $this->buildPartStatuses($index, $year, $day),
            'partStatus' => in_array($part, $parts, true) ? 'not_started' : null,
            'selectedYear' => $year,
            'selectedDay' => $day,
            'selectedPart' => $part,
            'puzzlePart1' => $part1Puzzle,
            'puzzlePart2' => $part2Puzzle,
            'input' => $input,
            'results' => $results,
        ]);
    }

    #[Route('/admin/solutions', name: 'aoc_admin_solutions', methods: ['GET'])]
    public function adminSolutions(SolutionRegistry $solutionRegistry): Response
    {
        return $this->render('puzzle/admin_solutions.html.twig', [
            'solutionIndex' => $solutionRegistry->index(),
        ]);
    }

    #[Route('/{year}/{day}/{part}/solve', name: 'aoc_mark_solved', requirements: ['year' => '\d{4}', 'day' => '\d{1,2}', 'part' => '[1-2]'], methods: ['POST'])]
    public function markSolved(int $year, int $day, int $part, SolutionRegistry $solutionRegistry): RedirectResponse
    {
        $parts = $this->partsForDay($solutionRegistry->index(), $year, $day);
        if (!in_array($part, $parts, true)) {
            throw $this->createNotFoundException('Puzzle not found.');
        }

        try {
            $this->solvedPartStore->markSolved($year, $day, $part);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('aoc_part', [
            'year' => $year,
            'day' => $day,
            'part' => $part,
        ]);
    }

    #[Route('/{year}/{day}/{part}/open', name: 'aoc_mark_open', requirements: ['year' => '\d{4}', 'day' => '\d{1,2}', 'part' => '[1-2]'], methods: ['POST'])]
    public function markOpen(int $year, int $day, int $part, SolutionRegistry $solutionRegistry): RedirectResponse
    {
        $parts = $this->partsForDay($solutionRegistry->index(), $year, $day);
        if (!in_array($part, $parts, true)) {
            throw $this->createNotFoundException('Puzzle not found.');
        }

        try {
            $this->solvedPartStore->markOpen($year, $day, $part);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('aoc_part', [
            'year' => $year,
            'day' => $day,
            'part' => $part,
        ]);
    }

    /**
     * @param array<int, array<int, int[]>> $index
     *
     * @return int[]
     */
    private function daysForYear(array $index, int $year): array
    {
        return array_keys($index[$year] ?? []);
    }

    /**
     * @param array<int, array<int, int[]>> $index
     *
     * @return int[]
     */
    private function partsForDay(array $index, int $year, int $day): array
    {
        return $index[$year][$day] ?? [];
    }

    /**
     * @param array<int, array<int, int[]>> $index
     *
     * @return array<int, array<int, string>>
     */
    private function buildDayProgresses(array $index, int $year): array
    {
        $progresses = [];

        foreach ($index[$year] ?? [] as $day => $parts) {
            $progresses[$day] = [
                1 => $this->statusForPart($parts, $year, (int) $day, 1),
                2 => $this->statusForPart($parts, $year, (int) $day, 2),
            ];
        }

        return $progresses;
    }

    /**
     * @param array<int, array<int, int[]>> $index
     *
     * @return array<int, string>
     */
    private function buildPartStatuses(array $index, int $year, int $day): array
    {
        $parts = $this->partsForDay($index, $year, $day);

        return [
            1 => $this->statusForPart($parts, $year, $day, 1),
            2 => $this->statusForPart($parts, $year, $day, 2),
        ];
    }

    /**
     * @param int[] $availableParts
     */
    private function statusForPart(array $availableParts, int $year, int $day, int $part): string
    {
        if (!in_array($part, $availableParts, true)) {
            return '';
        }

        return $this->solvedPartStore->isSolved($year, $day, $part) ? 'solved' : 'not_started';
    }
}
