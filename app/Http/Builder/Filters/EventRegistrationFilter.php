<?php

namespace App\Http\Builder\Filters;

use Illuminate\Database\Eloquent\Builder;

class EventRegistrationFilter extends AbstractFilter
{
    public const ID = 'id';
    public const EVENT_ID = 'event_id';
    public const USER_ID = 'user_id';

    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::EVENT_ID => [$this, 'eventId'],
            self::USER_ID => [$this, 'userId'],
        ];
    }

    public function id(Builder $builder, int $value): void
    {
        $builder->where('id', '=', $value);
    }

    public function eventId(Builder $builder, int $value): void
    {
        $builder->where('event_id', '=', $value);
    }

    public function userId(Builder $builder, int $value): void
    {
        $builder->where('user_id', '=', $value);
    }
}
