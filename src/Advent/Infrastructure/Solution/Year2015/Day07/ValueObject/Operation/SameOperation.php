<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation;

class SameOperation implements OperationInterface
{

    public function execute(int $valueA, ?int $valueB): int
    {
        return $valueA;
    }

    public function getName(): string
    {
        return 'SAME';
    }
}
