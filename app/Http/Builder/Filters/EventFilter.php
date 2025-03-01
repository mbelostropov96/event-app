<?php

namespace App\Http\Builder\Filters;

use Illuminate\Database\Eloquent\Builder;

class EventFilter extends AbstractFilter
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const LOCATION = 'location';
    public const START_DATE = 'start_date';
    public const START_TIME = 'start_time';
    public const PRICE = 'price';
    public const TYPE = 'type';

    protected array $filters = [
        self::ID => ['eq'],
        self::TITLE => ['like'],
        self::LOCATION => ['like'],
        self::START_DATE => ['eq', 'gt', 'lt'],
        self::START_TIME => ['eq', 'gt', 'lt'],
        self::PRICE => ['eq', 'gt', 'lt'],
        self::TYPE => ['eq'],
    ];

    protected function getCallbacks(): array
    {
        return [
            self::ID => [$this, 'id'],
            self::TITLE => [$this, 'filterTitle'],
            self::LOCATION => [$this, 'filterLocation'],
            self::START_DATE => [$this, 'filterStartDate'],
            self::START_TIME => [$this, 'filterStartTime'],
            self::PRICE => [$this, 'filterPrice'],
            self::TYPE => [$this, 'filterType'],
        ];
    }

    public function id(Builder $builder, int $value): void
    {
        $builder->where('id', '=', $value);
    }

    protected function filterTitle(Builder $builder, $value): void
    {
        $builder->where('title', 'like', "%{$value}%");
    }

    protected function filterLocation(Builder $builder, $value): void
    {
        $builder->where('location', 'like', "%{$value}%");
    }

    protected function filterStartDate(Builder $builder, $value, $operator = '='): void
    {
        $builder->where('start_date', $operator, $value);
    }

    protected function filterStartTime(Builder $builder, $value, $operator = '='): void
    {
        $builder->where('start_time', $operator, $value);
    }

    protected function filterPrice(Builder $builder, $value, $operator = '='): void
    {
        $builder->where('price', $operator, $value);
    }

    protected function filterType(Builder $builder, $value): void
    {
        $builder->where('type', $value);
    }
}
