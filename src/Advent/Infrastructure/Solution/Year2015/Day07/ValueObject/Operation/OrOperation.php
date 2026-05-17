<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation;

class OrOperation implements OperationInterface
{

    public function execute(int $valueA, ?int $valueB): int
    {
        if ($valueB === null) {
            throw new \InvalidArgumentException("OR operation requires two input values.");
        }
        return $valueA | $valueB;
    }

    public function getName(): string
    {
        return 'OR';
    }
}
