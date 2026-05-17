<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation;

use InvalidArgumentException;

final class OperationFactory
{
    public function create(string $operationName): OperationInterface
    {
        switch ($operationName) {
            case 'AND':
                return new AndOperation();
            case 'SAME':
                return new SameOperation();
            case 'OR':
                return new OrOperation();
            case 'RSHIFT':
                return new RShiftOperation();
            case 'LSHIFT':
                return new LShiftOperation();
            case 'NOT':
                return new NotOperation();
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unsupported operation type: "%s".',
                    $operationName
                ));
        }
    }
}
