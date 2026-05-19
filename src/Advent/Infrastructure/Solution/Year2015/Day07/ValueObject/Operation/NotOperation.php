<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation;

class NotOperation implements OperationInterface
{

    public function execute(int $valueA, ?int $valueB): int
    {
        return bindec(substr(decbin(~ $valueA), -16));
    }

    public function getName(): string
    {
        return 'NOT';
    }
}
