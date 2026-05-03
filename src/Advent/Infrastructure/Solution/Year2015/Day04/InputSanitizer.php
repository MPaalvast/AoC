<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day04;

use App\Advent\Infrastructure\Solution\Year2015\Day04\ValueObject\MD5Finder;

final class InputSanitizer
{
    public function sanitize(string $input): MD5Finder
    {
        $rows = preg_split("/\r\n|\n|\r/", $input);

        return new MD5Finder($rows[0]);
    }
}
