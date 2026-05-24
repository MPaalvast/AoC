<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day08;

use App\Advent\Infrastructure\Solution\Year2015\Day08\ValueObject\StringCounter;

class InputSanitizer
{
    public function sanitize(string $input): StringCounter
    {
        $strings =  preg_split("/\r\n|\n|\r/", $input);

        return new StringCounter($strings);
    }
}
