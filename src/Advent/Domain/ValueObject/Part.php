<?php

declare(strict_types=1);

namespace App\Advent\Domain\ValueObject;

final readonly class Part
{
    public function __construct(private int $value)
    {
        if ($value < 1 || $value > 2) {
            throw new \InvalidArgumentException('Part must be either 1 or 2.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
