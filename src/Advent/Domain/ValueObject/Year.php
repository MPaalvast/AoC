<?php

declare(strict_types=1);

namespace App\Advent\Domain\ValueObject;

final readonly class Year
{
    public function __construct(private int $value)
    {
        if ($value < 2015 || $value > 2100) {
            throw new \InvalidArgumentException('Year must be between 2015 and 2100.');
        }
    }

    public function value(): int
    {
        return $this->value;
    }
}
