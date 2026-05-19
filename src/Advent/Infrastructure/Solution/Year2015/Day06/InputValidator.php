<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day06;

use InvalidArgumentException;
final class InputValidator
{
    public function validate(string $input): void
    {
        // Split by newlines and remove empty lines
        $rows = array_filter(explode("\n", $input), fn(string $line) => trim($line) !== '');

        foreach ($rows as $row) {
            $trimmedRow = trim($row);

            // Validate each row individually. Negate (!) so we throw on mismatch.
            if (!preg_match('/^(turn (on|off)|toggle) \d{1,3},\d{1,3} through \d{1,3},\d{1,3}$/', $trimmedRow)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid row format: "%s".', $trimmedRow)
                );
            }
        }
    }
}
