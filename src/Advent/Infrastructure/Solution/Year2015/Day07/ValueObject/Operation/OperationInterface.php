<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation;

interface OperationInterface
{
    /**
     * Voert de operatie uit op de gegeven waarden.
     * @param int $valueA De eerste inputwaarde.
     * @param int|null $valueB De tweede optionele inputwaarde.
     * @return int Het resultaat van de operatie.
     */
    public function execute(int $valueA, ?int $valueB): int;

    /**
     * Retourneert de naam van de operatie (voor logging of debugging).
     */
    public function getName(): string;
}
