<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day06;

use App\Advent\Infrastructure\Solution\Year2015\Day06\ValueObject\BrightnessLevel;
use App\Advent\Infrastructure\Solution\Year2015\Day06\ValueObject\LightGrid;
use App\Advent\Infrastructure\Solution\Year2015\Day06\ValueObject\LightState;

final class InputSanitizer
{
    public function sanitize(string $input): LightGrid
    {
        $lightActions =  preg_split("/\r\n|\n|\r/", $input);

        // 1. Instantiate the dedicated VOs, passing the raw actions array to each
        $lightState = new LightState($lightActions);
        $brightnessLevel = new BrightnessLevel($lightActions);

        // 2. Instantiate the Aggregate Root (LightGrid) with the created VOs
        return new LightGrid($lightState, $brightnessLevel);
    }
}
