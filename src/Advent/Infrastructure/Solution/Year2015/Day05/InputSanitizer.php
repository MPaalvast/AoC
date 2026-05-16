<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day05;

use App\Advent\Infrastructure\Solution\Year2015\Day05\ValueObject\NiceStringChecker;

final class InputSanitizer
{
    public function sanitize(string $input): NiceStringChecker
    {
        $strings =  preg_split("/\r\n|\n|\r/", $input);

        return new NiceStringChecker($strings);
    }
}
