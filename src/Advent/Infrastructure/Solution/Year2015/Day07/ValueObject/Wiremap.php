<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject;

use InvalidArgumentException;

class Wiremap
{
    public array $actions = [];
    public array $results = [];

    public function setAction(WireAction $action): void
    {
        $this->actions[] = $action;
    }

    public function setResult(string $key, int $value): void
    {
        $this->results[$key] = $value;
    }

    public function getResult(string $key): int
    {
        return $this->results[$key];
    }

    public function getTotal(): int
    {
        ksort($this->results);
        $key = array_key_first($this->results);
        return $this->results[$key] ?? 0;
    }

    public function renderActions(): void
    {
        $firstAction = null;
        while (!empty($this->actions)) {
            $currentAction = array_shift($this->actions);

            if ($firstAction !== null && $firstAction->getSignature() === $currentAction->getSignature()) {
                throw new InvalidArgumentException('Loop found!!!');
            }
            if (null === $firstAction) {
                $firstAction = $currentAction;
            }
            if ($this->checkAction($currentAction)) {
                $this->handleAction($currentAction);
                $firstAction = null;
                continue;
            }
            $this->actions[] = $currentAction;
        }
    }

    private function checkAction(WireAction $action): bool
    {
        foreach ($action->getInputFields() as $inputField) {
            if (is_numeric($inputField)) {
                continue;
            }
            if (!isset($this->results[$inputField])) {
                return false;
            }
        }
        return true;
    }

    private function handleAction(WireAction $action): void
    {
        $this->setResult($action->getOutputField(), $action->execute($this->results));
    }

    public function createIndependentCopy(): self
    {
        $clone = new self();
        $clone->results = $this->results;
        $clone->actions = $this->actions;

        return $clone;
    }
}
