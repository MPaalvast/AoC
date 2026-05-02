<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day03;

use App\Advent\Infrastructure\Solution\Year2015\Day03\ValueObject\NavigationMap;

final class InputSanitizer
{
    public function sanitize(string $input): NavigationMap
    {
        $rows = preg_split("/\r\n|\n|\r/", $input);

        return new NavigationMap(str_split($rows[0]));
    }
}
