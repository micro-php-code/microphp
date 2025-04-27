<?php

declare(strict_types=1);

namespace MicroPHP\Framework\Database\Cast;

use Cycle\ORM\Parser\CastableInterface;
use Cycle\ORM\Parser\UncastableInterface;
use DateTimeImmutable;

class IntTimeTypeCast implements CastableInterface, UncastableInterface
{
    private array $rules = [];

    public function cast(array $data): array
    {
        $values = $data;
        foreach ($this->rules as $column => $rule) {
            if (! isset($values[$column])) {
                continue;
            }

            $values[$column] = (new DateTimeImmutable())->setTimestamp((int) $values[$column]);
        }

        return $values;
    }

    public function setRules(array $rules): array
    {
        foreach ($rules as $key => $rule) {
            if ($rule === IntTimeTypeCast::class) {
                unset($rules[$key]);
                $this->rules[$key] = $rule;
            }
        }

        return $rules;
    }

    public function uncast(array $data): array
    {
        $values = $data;
        foreach ($this->rules as $column => $rule) {
            if (! isset($values[$column]) || ! $values[$column] instanceof DateTimeImmutable) {
                continue;
            }

            $values[$column] = $values[$column]->getTimestamp();
        }

        return $values;
    }
}
