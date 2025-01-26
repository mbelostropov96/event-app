<?php

namespace App\Http\Builder\Sorters;

use Illuminate\Database\Eloquent\Builder;

class EventRegistrationSorter extends AbstractSorter
{
    public const ID = 'id';

    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
        ];
    }

    public function id(Builder $builder, string $value): void
    {
        $builder->orderBy('id', $value);
    }
}
