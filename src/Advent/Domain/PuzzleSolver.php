<?php

declare(strict_types=1);

namespace App\Advent\Domain;

interface PuzzleSolver
{
    public function year(): int;

    public function day(): int;

    public function part(): int;

    public function solve(string $input): string;
}
