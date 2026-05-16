<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day05\ValueObject;

class NiceStringChecker
{
    public function __construct(
        public array $strings
    ){
    }

    public function getNiceStringsPart1(): int
    {
        $total = 0;
        foreach ($this->strings as $string) {
            if (
                $this->findDoubleLetters($string) &&
                !$this->findLinkedLetters($string) &&
                $this->findVowels($string)
            ) {
                $total++;
            }
        }

        return $total;
    }

    public function getNiceStringsPart2(): int
    {
        $total = 0;
        foreach ($this->strings as $string) {
            if (
                $this->findDoublePairs($string) &&
                $this->containsSplitRepeatedLetter($string)
            ) {
                $total++;
            }
        }

        return $total;
    }

    /**
     * The string has to have at least 1 double letter in it
     */
    private function findDoubleLetters(string $string): bool
    {
        preg_match('/(aa)|(bb)|(cc)|(dd)|(ee)|(ff)|(gg)|(hh)|(ii)|(jj)|(kk)|(ll)|(mm)|(nn)|(oo)|(pp)|(qq)|(rr)|(ss)|(tt)|(uu)|(vv)|(ww)|(xx)|(yy)|(zz)/', $string, $matches);
        if (empty($matches)) {
            return false;
        }
        return true;
    }

    /**
     * The string can not contain any of the pairs ab,cd,pq,xy
     */
    private function findLinkedLetters(string $string): bool
    {
        preg_match('/(ab)|(cd)|(pq)|(xy)/', $string, $matches);
        if (empty($matches)) {
            return false;
        }
        return true;
    }

    /**
     * The string has to have alt least 3 vowels in it.
     */
    private function findVowels(string $string): bool
    {
        $minOccurrences = 3;
        preg_match_all('/[aeiou]/', $string, $matches);
        if (count($matches[0]) >= $minOccurrences) {
            return true;
        }
        return false;
    }

    /**
     * Find double pairs in string with key xx like (jg in jgvebdjgben, hh in djfhhesbfhhjn)
     */
    private function findDoublePairs(string $string): bool
    {
        $stringParts = str_split($string);
        $part1 = array_shift($stringParts);
        $part2 = array_shift($stringParts);

        while (!empty($stringParts)) {
            if (str_contains(implode('', $stringParts), sprintf("%s%s", $part1, $part2))) {
                return true;
            }
            $part1 = $part2;
            $part2 = array_shift($stringParts);
        }
        return false;
    }

    /**
     * Find letters with 1 different letter in between like aga, efe, nan
     */
    private function containsSplitRepeatedLetter(string $string): bool
    {
        $stringParts = str_split($string);
        $max = count($stringParts) - 2;
        for ($i = 0; $i < $max; $i++) {
            if ($stringParts[$i] === $stringParts[$i + 2]) {
                return true;
            }
        }
        return false;
    }
}
