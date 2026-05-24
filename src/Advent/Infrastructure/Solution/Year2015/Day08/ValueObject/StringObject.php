<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day08\ValueObject;

class StringObject
{
    public function __construct(
        private string $string
    ){
    }

    public function getLiteralChars(): int
    {
        return strlen($this->string);
    }

    public function getActualChars(): int
    {
        $string = substr($this->string, 1, -1);
        $string = $this->replaceHexChars($string);
        $string = $this->replaceStrings($string);

        return strlen($string);
    }

    private function replaceHexChars(string $string): string
    {
        return preg_replace_callback('/\\\x([0-9A-Fa-f]{2})/', static function($matches) {
            return chr(hexdec($matches[1])); // Zet de hexadecimale waarde om naar een teken
        }, $string);
    }

    private function replaceStrings(string $string): string
    {
        return preg_replace_callback('/\\\(\\\)|\\\(\")/', static function($matches) {
            return $matches[2] ?? $matches[1];
        }, $string);
    }

    public function getEscapedChars(): int
    {
        $string = sprintf("\"%s\"", addslashes($this->string));
        return strlen($string);
    }
}
