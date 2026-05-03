<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day04\ValueObject;

readonly class MD5Finder
{
    public function __construct(
        private string $input
    ){
    }

    public function findKey(string $prefix): int
    {
        $i = 1;
        while (true) {
            if (str_starts_with(md5($this->input . $i), $prefix)) {
                break;
            }
            $i++;
        }

        return $i;
    }
}
