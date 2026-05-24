<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day08\ValueObject;

class StringCounter
{
    /** @var array[StringObject]  */
    private array $strings = [];

    public function __construct(array $strings)
    {
        foreach ($strings as $string) {
            $this->addString(new StringObject($string));
        }
    }

    public function addString(StringObject $string): void
    {
        $this->strings[] = $string;
    }

    public function calculateLiteralMinusActual(): int
    {
        $stringData = $this->getStringData();

        return $stringData['literal'] - $stringData['actual'];
    }

    public function getStringData(): array
    {
        $stringData = [
            'literal' => 0,
            'actual' => 0,
            'escaped' => 0
        ];

        /** @var StringObject $string */
        foreach ($this->strings as $string) {
            $stringData['literal'] += $string->getLiteralChars();
            $stringData['actual'] += $string->getActualChars();
            $stringData['escaped'] += $string->getEscapedChars();
        }

        return $stringData;
    }

    public function calculateEscapedMinusLiteral(): int
    {
        $stringData = $this->getStringData();

        return $stringData['escaped'] - $stringData['literal'];
    }
}
