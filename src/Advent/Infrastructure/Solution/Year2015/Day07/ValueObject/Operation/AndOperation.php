<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation;

final class AndOperation implements OperationInterface
{

    public function execute(int $valueA, ?int $valueB): int
    {
        if ($valueB === null) {
            throw new \InvalidArgumentException("AND operation requires two input values.");
        }
        return $valueA & $valueB;
    }

    public function getName(): string
    {
        return 'AND';
    }
}
