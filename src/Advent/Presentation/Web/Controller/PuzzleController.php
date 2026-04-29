<?php

declare(strict_types=1);

namespace App\Advent\Presentation\Web\Controller;

use App\Advent\Application\Command\RunSolution\RunSolutionCommand;
use App\Advent\Application\Command\RunSolution\RunSolutionHandler;
use App\Advent\Application\Query\GetPuzzle\GetPuzzleHandler;
use App\Advent\Application\Query\GetPuzzle\GetPuzzleQuery;
use App\Advent\Domain\PuzzleRepository;
use App\Advent\Domain\SolutionRegistry;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PuzzleController extends AbstractController
{
    #[Route('/', name: 'aoc_home', methods: ['GET'])]
    public function home(PuzzleRepository $puzzleRepository): Response
    {
        return $this->render('puzzle/menu.html.twig', [
            'years' => $puzzleRepository->availableYears(),
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
    public function year(int $year, PuzzleRepository $puzzleRepository): Response
    {
        return $this->render('puzzle/menu.html.twig', [
            'years' => $puzzleRepository->availableYears(),
            'days' => $puzzleRepository->availableDays($year),
            'parts' => [],
            'dayProgresses' => $puzzleRepository->dayProgresses($year),
            'partStatuses' => [],
            'selectedYear' => $year,
            'selectedDay' => null,
            'selectedPart' => null,
        ]);
    }

    #[Route('/{year}/{day}', name: 'aoc_day', requirements: ['year' => '\d{4}', 'day' => '\d{1,2}'], methods: ['GET'])]
    public function day(int $year, int $day, PuzzleRepository $puzzleRepository): Response
    {
        return $this->render('puzzle/menu.html.twig', [
            'years' => $puzzleRepository->availableYears(),
            'days' => $puzzleRepository->availableDays($year),
            'parts' => $puzzleRepository->availableParts($year, $day),
            'dayProgresses' => $puzzleRepository->dayProgresses($year),
            'partStatuses' => [
                1 => $puzzleRepository->partStatus($year, $day, 1),
                2 => $puzzleRepository->partStatus($year, $day, 2),
            ],
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
        PuzzleRepository $puzzleRepository,
        GetPuzzleHandler $getPuzzleHandler,
        RunSolutionHandler $runSolutionHandler
    ): Response {
        $puzzle = $getPuzzleHandler(new GetPuzzleQuery($year, $day, $part));
        if ($puzzle === null) {
            throw $this->createNotFoundException('Puzzle not found.');
        }

        $input = '';
        $result = null;
        if ($request->isMethod('POST')) {
            $input = (string) $request->request->get('input', '');

            try {
                $result = $runSolutionHandler(new RunSolutionCommand($year, $day, $part, $input));
            } catch (\RuntimeException $exception) {
                $this->addFlash('error', $exception->getMessage());
            }
        }

        return $this->render('puzzle/run.html.twig', [
            'years' => $puzzleRepository->availableYears(),
            'days' => $puzzleRepository->availableDays($year),
            'parts' => $puzzleRepository->availableParts($year, $day),
            'dayProgresses' => $puzzleRepository->dayProgresses($year),
            'partStatuses' => [
                1 => $puzzleRepository->partStatus($year, $day, 1),
                2 => $puzzleRepository->partStatus($year, $day, 2),
            ],
            'partStatus' => $puzzleRepository->partStatus($year, $day, $part),
            'selectedYear' => $year,
            'selectedDay' => $day,
            'selectedPart' => $part,
            'puzzle' => $puzzle,
            'input' => $input,
            'result' => $result,
        ]);
    }

    #[Route('/admin/solutions', name: 'aoc_admin_solutions', methods: ['GET'])]
    public function adminSolutions(SolutionRegistry $solutionRegistry, PuzzleRepository $puzzleRepository): Response
    {
        $years = $puzzleRepository->availableYears();
        $daysByYear = [];
        foreach ($years as $year) {
            $daysByYear[$year] = $puzzleRepository->availableDays($year);
        }

        return $this->render('puzzle/admin_solutions.html.twig', [
            'solutionIndex' => $solutionRegistry->index(),
            'years' => $years,
            'daysByYear' => $daysByYear,
        ]);
    }

    #[Route('/admin/years', name: 'aoc_admin_add_year', methods: ['POST'])]
    public function addYear(Request $request, PuzzleRepository $puzzleRepository): RedirectResponse
    {
        $year = (int) $request->request->get('year', 0);
        $maxDays = (int) $request->request->get('max_days', 25);

        try {
            $puzzleRepository->addYear($year, $maxDays);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('aoc_admin_solutions');
    }

    #[Route('/admin/days', name: 'aoc_admin_add_day', methods: ['POST'])]
    public function addDay(Request $request, PuzzleRepository $puzzleRepository): RedirectResponse
    {
        $year = (int) $request->request->get('year', 0);
        $day = (int) $request->request->get('day', 0);

        try {
            $puzzleRepository->addDay($year, $day);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('aoc_admin_solutions');
    }

    #[Route('/{year}/{day}/{part}/solve', name: 'aoc_mark_solved', requirements: ['year' => '\d{4}', 'day' => '\d{1,2}', 'part' => '[1-2]'], methods: ['POST'])]
    public function markSolved(int $year, int $day, int $part, PuzzleRepository $puzzleRepository): RedirectResponse
    {
        try {
            $puzzleRepository->markPartSolved($year, $day, $part);
        } catch (\Throwable $exception) {
            $this->addFlash('error', $exception->getMessage());
        }

        return $this->redirectToRoute('aoc_part', [
            'year' => $year,
            'day' => $day,
            'part' => $part,
        ]);
    }
}
