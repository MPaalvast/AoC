<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day03\ValueObject;

final class NavigationMap
{
    public function __construct(
        public array $directions
    ){
    }

    public function getHousesVisitedBySanta(): int
    {
        $visited = [];
        $currentLocation = ['x' => 0, 'y' => 0];

        $visited[] = $this->getCurrentLocationKey($currentLocation);

        foreach ($this->directions as $direction) {
            $currentLocation = $this->walk($currentLocation, $direction);
            $visited[] = $this->getCurrentLocationKey($currentLocation);
        }
        return count(array_unique($visited));
    }

    public function getHousesVisitedBySantaAndHelper(): int
    {
        $visited = [];
        $santaCurrentLocation = ['x' => 0, 'y' => 0];
        $helperCurrentLocation = ['x' => 0, 'y' => 0];

        $visited[] = $this->getCurrentLocationKey($santaCurrentLocation);

        foreach ($this->directions as $key => $direction) {
            if ($key % 2 === 0) {
                $santaCurrentLocation = $this->walk($santaCurrentLocation, $direction);
                $visited[] = $this->getCurrentLocationKey($santaCurrentLocation);
                continue;
            }
            $helperCurrentLocation = $this->walk($helperCurrentLocation, $direction);
            $visited[] = $this->getCurrentLocationKey($helperCurrentLocation);
        }

        return count(array_unique($visited));
    }

    private function getCurrentLocationKey(array $location): string
    {
        return $location['x'] . '-' . $location['y'];
    }

    private function walk(array $currentLocation, string $direction): array
    {
        $newLocation = $currentLocation;
        match ($direction) {
            '^' => --$newLocation['x'],
            '>' => ++$newLocation['y'],
            'v' => ++$newLocation['x'],
            '<' => --$newLocation['y'],
        };

        return $newLocation;
    }
}
