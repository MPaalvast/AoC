<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day08;

use InvalidArgumentException;

class InputValidator
{
    public function validate(string $input): void
    {
        // Split by newlines and remove empty lines
        $rows = array_filter(explode("\n", $input), fn(string $line) => trim($line) !== '');

        foreach ($rows as $row) {
            $trimmedRow = trim($row);

            // Validate each row individually. Negate (!) so we throw on mismatch.
            if (!preg_match('/^\"[\w\\\"]{0,}\"$/', $trimmedRow)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid row format: "%s".', $trimmedRow)
                );
            }
        }
    }
}
