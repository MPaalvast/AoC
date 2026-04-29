<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day02\ValueObject;

/**
 * Immutable value object representing a package's dimensions.
 */
final readonly class Package
{
    public function __construct(
        public int $length,
        public int $width,
        public int $height,
    ) {}

    public function surfaceArea(): int
    {
        $sides = [
            $this->length * $this->width,
            $this->width * $this->height,
            $this->height * $this->length,
        ];

        return (array_sum($sides) * 2) + min($sides);
    }

    /**
     * Bereken de lengte van het lint (perimeter van de twee kleinste zijden)
     */
    public function ribbonLength(): int
    {
        $dimensions = [$this->length, $this->width, $this->height];
        sort($dimensions);

        // 2x de kleinste zijde + 2x de middelste zijde
        return (2 * $dimensions[0]) + (2 * $dimensions[1]);
    }

    /**
     * Bereken de hoeveelheid lint voor de strik (volume)
     */
    public function bowSize(): int
    {
        return $this->length * $this->width * $this->height;
    }
}
