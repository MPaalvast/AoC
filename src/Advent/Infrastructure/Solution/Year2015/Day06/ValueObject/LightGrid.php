<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day06\ValueObject;

class LightGrid
{
    private LightState $lightState;
    private BrightnessLevel $brightnessLevel;
    public function __construct(
        LightState $lightState,
        BrightnessLevel $brightnessLevel
    ) {
        $this->lightState = $lightState;
        $this->brightnessLevel = $brightnessLevel;
    }

    /**
     * Processes actions and calculates the number of lit lights.
     */
    public function checkLitLights(): void
    {
        $this->lightState->applyActions();
    }

    public function getLit(): int
    {
        return $this->lightState->getLitCount();
    }

    /**
     * Processes actions and calculates the total brightness level.
     */
    public function checkBrightness(): void
    {
        $this->brightnessLevel->applyActions();
    }

    public function getBrightnessLevel(): int
    {
        return $this->brightnessLevel->getBrightnessSum();
    }
}
