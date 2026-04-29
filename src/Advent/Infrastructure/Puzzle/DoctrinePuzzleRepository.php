<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Puzzle;

use App\Advent\Domain\Puzzle;
use App\Advent\Domain\PuzzleRepository;
use App\Advent\Domain\ValueObject\Day;
use App\Advent\Domain\ValueObject\Part;
use App\Advent\Domain\ValueObject\Year;
use App\Entity\AocDay;
use App\Entity\AocYear;
use Doctrine\ORM\EntityManagerInterface;

final readonly class DoctrinePuzzleRepository implements PuzzleRepository
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    public function findPuzzle(int $year, int $day, int $part): ?Puzzle
    {
        if ($part < 1 || $part > 2) {
            return null;
        }

        $aocDay = $this->entityManager->getRepository(AocDay::class)->findOneBy([
            'dayNumber' => $day,
            'year' => $this->entityManager->getRepository(AocYear::class)->findOneBy(['yearNumber' => $year]),
        ]);

        if ($aocDay === null) {
            return null;
        }

        return new Puzzle(
            new Year($year),
            new Day($day),
            new Part($part),
            sprintf('Year %d Day %02d Part %d', $year, $day, $part),
            'Puzzle statement placeholder. Add your solver logic and input, then run and mark as solved when verified.'
        );
    }

    public function availableYears(): array
    {
        $years = $this->entityManager->createQueryBuilder()
            ->select('y.yearNumber')
            ->from(AocYear::class, 'y')
            ->orderBy('y.yearNumber', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        return array_map(static fn (mixed $year): int => (int) $year, $years);
    }

    public function availableDays(int $year): array
    {
        $days = $this->entityManager->createQueryBuilder()
            ->select('d.dayNumber')
            ->from(AocDay::class, 'd')
            ->innerJoin('d.year', 'y')
            ->andWhere('y.yearNumber = :year')
            ->setParameter('year', $year)
            ->orderBy('d.dayNumber', 'ASC')
            ->getQuery()
            ->getSingleColumnResult();

        return array_map(static fn (mixed $day): int => (int) $day, $days);
    }

    public function availableParts(int $year, int $day): array
    {
        return $this->partStatus($year, $day, 1) !== null ? [1, 2] : [];
    }

    public function dayProgresses(int $year): array
    {
        $rows = $this->entityManager->createQueryBuilder()
            ->select('d.dayNumber AS dayNumber, d.part1Status AS part1Status, d.part2Status AS part2Status')
            ->from(AocDay::class, 'd')
            ->innerJoin('d.year', 'y')
            ->andWhere('y.yearNumber = :year')
            ->setParameter('year', $year)
            ->orderBy('d.dayNumber', 'ASC')
            ->getQuery()
            ->getArrayResult();

        $progress = [];
        foreach ($rows as $row) {
            $progress[(int) $row['dayNumber']] = [
                1 => (string) $row['part1Status'],
                2 => (string) $row['part2Status'],
            ];
        }

        return $progress;
    }

    public function partStatus(int $year, int $day, int $part): ?string
    {
        $aocDay = $this->findAocDay($year, $day);
        if ($aocDay === null) {
            return null;
        }

        return $aocDay->getPartStatus($part);
    }

    public function markPartSolved(int $year, int $day, int $part): void
    {
        $aocDay = $this->findAocDay($year, $day);
        if ($aocDay === null) {
            throw new \RuntimeException('Day not found.');
        }

        $aocDay->markSolved($part);
        $this->entityManager->flush();
    }

    public function addYear(int $year, int $maxDays): void
    {
        if ($maxDays < 1 || $maxDays > 25) {
            throw new \InvalidArgumentException('maxDays must be between 1 and 25.');
        }

        $existing = $this->entityManager->getRepository(AocYear::class)->findOneBy(['yearNumber' => $year]);
        if ($existing !== null) {
            throw new \RuntimeException('Year already exists.');
        }

        $this->entityManager->persist(new AocYear($year, $maxDays));
        $this->entityManager->flush();
    }

    public function addDay(int $year, int $day): void
    {
        $aocYear = $this->entityManager->getRepository(AocYear::class)->findOneBy(['yearNumber' => $year]);
        if ($aocYear === null) {
            throw new \RuntimeException('Year not found.');
        }

        if ($day < 1 || $day > $aocYear->getMaxDays()) {
            throw new \InvalidArgumentException(sprintf('Day must be between 1 and %d for this year.', $aocYear->getMaxDays()));
        }

        $existing = $this->findAocDay($year, $day);
        if ($existing !== null) {
            throw new \RuntimeException('Day already exists.');
        }

        $this->entityManager->persist(new AocDay($aocYear, $day));
        $this->entityManager->flush();
    }

    private function findAocDay(int $year, int $day): ?AocDay
    {
        $aocYear = $this->entityManager->getRepository(AocYear::class)->findOneBy(['yearNumber' => $year]);
        if ($aocYear === null) {
            return null;
        }

        return $this->entityManager->getRepository(AocDay::class)->findOneBy([
            'year' => $aocYear,
            'dayNumber' => $day,
        ]);
    }
}
