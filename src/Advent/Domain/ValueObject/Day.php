<?php

declare(strict_types=1);

namespace App\Advent\Domain\ValueObject;

final readonly class Day
{
    public function __construct(private int $value)
    {
        if ($value < 1 || $value > 25) {
            throw new \InvalidArgumentException('Day must be between 1 and 25.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
