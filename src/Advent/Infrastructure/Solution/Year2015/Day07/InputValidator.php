<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07;

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
            if (!preg_match('/^(([a-z0-9]+)|([a-z0-9]+ (AND|OR|LSHIFT|RSHIFT) [a-z1-9]+)|NOT [a-z1-9]+) -> [a-z1-9]+$/', $trimmedRow)) {
                throw new InvalidArgumentException(
                    sprintf('Invalid row format: "%s".', $trimmedRow)
                );
            }
        }
    }
}
