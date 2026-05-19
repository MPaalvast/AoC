<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject;

use App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation\OperationInterface;

readonly class WireAction
{
    public function __construct(
        public OperationInterface $operation,
        public array  $inputFields,
        public string $outputField,
    )
    {
    }

    public function getInputFields(): array
    {
        return $this->inputFields;
    }

    public function getOutputField(): string
    {
        return $this->outputField;
    }

    public function getType(): string
    {
        return $this->operation->getName();
    }

    public function execute(array $results): int
    {
        $valueA = $this->getValue($results, $this->inputFields[0]);
        $valueB = $this->getValue($results, $this->inputFields[1] ?? null);

        return $this->operation->execute($valueA, $valueB);
    }

    private function getValue(array $results, string|int|null $inputValue):? int
    {
        if (is_numeric($inputValue)) {
            return (int)$inputValue;
        }

        if (is_string($inputValue)) {
            return (int)$results[$inputValue];
        }
        return null;
    }

    /**
     * Generert een unieke, consistente hash die de actie definieert.
     * Dit kan gebruikt worden om logische duplicaten te detecteren.
     */
    public function getSignature(): string
    {
        // We combineren alle unieke eigenschappen in een hash.
        // Dit zorgt ervoor dat twee acties met dezelfde structuur en type dezelfde hash krijgen.
        return md5(
            $this->getType() . "|" .
            $this->outputField . "|" .
            json_encode($this->inputFields, JSON_THROW_ON_ERROR)
        );
    }
}
