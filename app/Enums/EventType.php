<?php

namespace App\Enums;

enum EventType: string
{
    case CULTURAL = 'cultural';
    case SPORTS = 'sports';
    case EDUCATIONAL = 'educational';
    case ENTERTAINMENT = 'entertainment';
    case OTHER = 'other';

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function toArray(): array
    {
        return [
            self::CULTURAL->value => 'Культурное мероприятие',
            self::SPORTS->value => 'Спортивное мероприятие',
            self::EDUCATIONAL->value => 'Образовательное мероприятие',
            self::ENTERTAINMENT->value => 'Развлекательное мероприятие',
            self::OTHER->value => 'Другое',
        ];
    }
}
