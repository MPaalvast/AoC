<?php

namespace App\Advent\Infrastructure\Solution\Year2015\Day07;

use App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Operation\OperationFactory;
use App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\WireAction;
use App\Advent\Infrastructure\Solution\Year2015\Day07\ValueObject\Wiremap;

final class InputSanitizer
{
    public function sanitize(string $input): Wiremap
    {
        $wiremap = new Wiremap();
        $wireConnections =  preg_split("/\r\n|\n|\r/", $input);

        $operationFactory = new OperationFactory();

        foreach ($wireConnections as $wireConnection) {
            $connectionParts = explode(' ', $wireConnection);

            $operationString = $connectionParts[1] ?? '';
            try {
                if (in_array($operationString, ['AND', 'OR', 'RSHIFT', 'LSHIFT'], true)) {
                    $operation = $operationFactory->create($operationString);
                    $wiremap->setAction(new WireAction($operation, [$connectionParts[0], $connectionParts[2]], $connectionParts[4]));
                } elseif ($connectionParts[0] === 'NOT') {
                    $operation = $operationFactory->create($connectionParts[0]);
                    $wiremap->setAction(new WireAction($operation, [$connectionParts[1]], $connectionParts[3]));
                } elseif (is_numeric($connectionParts[0])) {
                    $wiremap->setResult($connectionParts[2], $connectionParts[0]);
                } else {
                    $operation = $operationFactory->create("SAME");
                    $wiremap->setAction(new WireAction($operation, [$connectionParts[0]], $connectionParts[2]));
                }
            } catch (\InvalidArgumentException $e) {
                // Opvangen van onbekende operatoren
                // In een productieomgeving loggen we dit en gaan we verder.
            }
        }

        return $wiremap;
    }
}
