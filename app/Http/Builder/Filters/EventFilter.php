<?php

namespace App\Http\Builder\Filters;

use Illuminate\Database\Eloquent\Builder;

class EventFilter extends AbstractFilter
{
    public const ID = 'id';
    public const DATE = 'date';

    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::DATE => [$this, 'date'],
        ];
    }

    public function id(Builder $builder, int $value): void
    {
        $builder->where('id', '=', $value);
    }

    public function date(Builder $builder, string $value): void
    {
        $builder->where('date', '=', $value);
    }
}
