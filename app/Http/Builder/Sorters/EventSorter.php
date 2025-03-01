<?php

namespace App\Http\Builder\Sorters;

use Illuminate\Database\Eloquent\Builder;

class EventSorter extends AbstractSorter
{
    public const ID = 'id';

    protected array $availableFields = [
        'title',
        'location',
        'start_date',
        'start_time',
        'price',
        'type'
    ];

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

    protected function sort(Builder $builder, string $field, string $direction): void
    {
        $builder->orderBy($field, $direction);
    }
}
