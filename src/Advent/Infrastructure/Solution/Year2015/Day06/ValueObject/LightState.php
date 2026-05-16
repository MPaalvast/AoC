<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day06\ValueObject;

class LightState
{
    /**
     * @var array<int, array<int, bool>>
     */
    private array $lightsOn = [];

    public function __construct(
        private readonly array $LightActions
    ) {}

    /**
     * Processes all actions and updates the internal light status.
     */
    public function applyActions(): void
    {
        $this->lightsOn = [];
        foreach ($this->LightActions as $actionRow) {
            $actionParts = explode(" ", $actionRow);
            if ($actionParts[0] === 'toggle') {
                [$startX, $startY] = explode(',', $actionParts[1]);
                [$endX, $endY] = explode(',', $actionParts[3]);
                $this->toggle((int)$startX, (int)$startY, (int)$endX, (int)$endY);
            } else {
                [$startX, $startY] = explode(',', $actionParts[2]);
                [$endX, $endY] = explode(',', $actionParts[4]);
                if ($actionParts[1] === 'on') {
                    $this->turnOn((int)$startX, (int)$startY, (int)$endX, (int)$endY);
                } else {
                    $this->turnOff((int)$startX, (int)$startY, (int)$endX, (int)$endY);
                }
            }
        }
    }

    /**
     * Counts the total number of lit lights.
     */
    public function getLitCount(): int
    {
        $total = 0;
        foreach ($this->lightsOn as $rowData) {
            $total += count($rowData);
        }
        return $total;
    }

    /**
     * Adds lights that are not in the array.
     */
    private function turnOn(int $startX, int $startY, int $endX, int $endY): void
    {
        for ($i = $startX; $i <= $endX; $i++) {
            for ($j = $startY; $j <= $endY; $j++) {
                $this->lightsOn[$i][$j] = true;
            }
        }
    }

    /**
     * Removes the on lights from the array.
     */
    private function turnOff(int $startX, int $startY, int $endX, int $endY): void
    {
        for ($i = $startX; $i <= $endX; $i++) {
            for ($j = $startY; $j <= $endY; $j++) {
                if (isset($this->lightsOn[$i][$j])) {
                    unset($this->lightsOn[$i][$j]);
                }
            }
        }
    }

    /**
     * Toggles the on lights from the array and adds the lights that are not in the array.
     */
    private function toggle(int $startX, int $startY, int $endX, int $endY): void
    {
        for ($i = $startX; $i <= $endX; $i++) {
            for ($j = $startY; $j <= $endY; $j++) {
                if (isset($this->lightsOn[$i][$j])) {
                    unset($this->lightsOn[$i][$j]);
                } else {
                    $this->lightsOn[$i][$j] = true;
                }
            }
        }
    }
}
