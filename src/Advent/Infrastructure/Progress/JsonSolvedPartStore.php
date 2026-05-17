<?php

declare(strict_types=1);

namespace App\Advent\Infrastructure\Progress;

final class JsonSolvedPartStore
{
    public function __construct(private readonly string $storagePath)
    {
    }

    public function isSolved(int $year, int $day, int $part): bool
    {
        $data = $this->read();

        return isset($data[(string) $year][(string) $day][(string) $part]);
    }

    public function markSolved(int $year, int $day, int $part): void
    {
        $data = $this->read();
        $data[(string) $year][(string) $day][(string) $part] = true;
        $this->write($data);
    }

    public function markOpen(int $year, int $day, int $part): void
    {
        $data = $this->read();
        unset($data[(string) $year][(string) $day][(string) $part]);

        if (($data[(string) $year][(string) $day] ?? []) === []) {
            unset($data[(string) $year][(string) $day]);
        }
        if (($data[(string) $year] ?? []) === []) {
            unset($data[(string) $year]);
        }

        $this->write($data);
    }

    /**
     * @return array<string, array<string, array<string, bool>>>
     */
    private function read(): array
    {
        if (!is_file($this->storagePath)) {
            return [];
        }

        $content = file_get_contents($this->storagePath);
        if ($content === false || trim($content) === '') {
            return [];
        }

        $decoded = json_decode($content, true);
        if (!is_array($decoded)) {
            return [];
        }

        $normalized = [];
        foreach ($decoded as $year => $days) {
            if (!is_array($days)) {
                continue;
            }

            foreach ($days as $day => $parts) {
                if (!is_array($parts)) {
                    continue;
                }

                foreach ($parts as $part => $solved) {
                    if ($solved) {
                        $normalized[(string) $year][(string) $day][(string) $part] = true;
                    }
                }
            }
        }

        return $normalized;
    }

    /**
     * @param array<string, array<string, array<string, bool>>> $data
     */
    private function write(array $data): void
    {
        $directory = dirname($this->storagePath);
        if (!is_dir($directory) && !mkdir($directory, 0777, true) && !is_dir($directory)) {
            throw new \RuntimeException('Kon opslagmap voor solved status niet aanmaken.');
        }

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
        if ($json === false) {
            throw new \RuntimeException('Kon solved status niet serialiseren naar JSON.');
        }

        if (file_put_contents($this->storagePath, $json . PHP_EOL, LOCK_EX) === false) {
            throw new \RuntimeException('Kon solved status JSON niet wegschrijven.');
        }
    }
}
