<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Solution\Year2015\Day02;

use App\Advent\Infrastructure\Solution\Year2015\Day02\ValueObject\Package;

final class InputSanitizer
{
    public function sanitize(string $input): array
    {
        $packages = [];
        $rows = preg_split("/\r\n|\n|\r/", $input);
        foreach ($rows as $row) {
            [$length, $width, $height] = explode('x', $row);
            $packages[] = new Package(
                (int) $length,
                (int) $width,
                (int) $height
            );
        }

        return $packages;
    }
}
