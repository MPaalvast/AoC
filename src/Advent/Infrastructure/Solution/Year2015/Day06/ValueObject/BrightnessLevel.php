<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day06\ValueObject;

class BrightnessLevel
{
    /**
     * @var array<int, array<int, int>>
     */
    private array $brightness = [];

    public function __construct(
        private readonly array $LightActions
    ) {}

    /**
     * Processes all actions and updates the internal brightness levels.
     */
    public function applyActions(): void
    {
        $this->brightness = [];
        foreach ($this->LightActions as $actionRow) {
            $actionParts = explode(" ", $actionRow);
            if ($actionParts[0] === 'toggle') {
                [$startX, $startY] = explode(',', $actionParts[1]);
                [$endX, $endY] = explode(',', $actionParts[3]);
                $this->addLevel((int)$startX, (int)$startY, (int)$endX, (int)$endY, 2);
            } else {
                [$startX, $startY] = explode(',', $actionParts[2]);
                [$endX, $endY] = explode(',', $actionParts[4]);
                if ($actionParts[1] === 'on') {
                    $this->addLevel((int)$startX, (int)$startY, (int)$endX, (int)$endY);
                } else {
                    $this->removeLevel((int)$startX, (int)$startY, (int)$endX, (int)$endY);
                }
            }
        }
    }

    /**
     * Sums all the brightness levels in the grid.
     */
    public function getBrightnessSum(): int
    {
        $total = 0;
        foreach ($this->brightness as $rowData) {
            $total += array_sum($rowData);
        }
        return $total;
    }

    /**
     * Adds the specified brightness level to the lights.
     */
    private function addLevel(int $startX, int $startY, int $endX, int $endY, $brightness = 1): void
    {
        for ($i = $startX; $i <= $endX; $i++) {
            if (!isset($this->brightness[$i])) {
                $this->brightness[$i] = [];
            }
            for ($j = $startY; $j <= $endY; $j++) {
                if (!isset($this->brightness[$i][$j])) {
                    $this->brightness[$i][$j] = 0;
                }
                $this->brightness[$i][$j] += $brightness;
            }
        }
    }

    /**
     * Removes 1 level of brightness from the lights and removes the value if it is 0.
     */
    private function removeLevel(int $startX, int $startY, int $endX, int $endY): void
    {
        for ($i = $startX; $i <= $endX; $i++) {
            for ($j = $startY; $j <= $endY; $j++) {
                if (isset($this->brightness[$i][$j])) {
                    --$this->brightness[$i][$j];
                    if ($this->brightness[$i][$j] <= 0) {
                        unset($this->brightness[$i][$j]);
                    }
                }
            }
        }
    }
}
