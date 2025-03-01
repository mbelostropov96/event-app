<?php

namespace Database\Seeders;

use App\Enums\EventType;
use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        $events = [
            [
                'title' => 'Концерт классической музыки',
                'description' => 'Великолепный концерт классической музыки в исполнении симфонического оркестра',
                'location' => 'Концертный зал (ул. Музыкальная, 1)',
                'start_date' => now()->addDays(5),
                'start_time' => '19:00',
                'price' => 1500,
                'type' => EventType::CULTURAL,
            ],
            [
                'title' => 'Футбольный матч',
                'description' => 'Важный матч чемпионата между местными командами',
                'location' => 'Городской стадион (ул. Спортивная, 10)',
                'start_date' => now()->addDays(2),
                'start_time' => '18:30',
                'price' => 800,
                'type' => EventType::SPORTS,
            ],
            [
                'title' => 'Мастер-класс по программированию',
                'description' => 'Практический мастер-класс по современным технологиям разработки',
                'location' => 'Технопарк (пр. Инновационный, 5)',
                'start_date' => now()->addDays(7),
                'start_time' => '11:00',
                'price' => 2000,
                'type' => EventType::EDUCATIONAL,
            ],
            [
                'title' => 'Стендап шоу',
                'description' => 'Вечер юмора с участием известных комиков',
                'location' => 'Клуб "Смех" (ул. Весёлая, 15)',
                'start_date' => now()->addDays(3),
                'start_time' => '20:00',
                'price' => 1200,
                'type' => EventType::ENTERTAINMENT,
            ],
            [
                'title' => 'Выставка современного искусства',
                'description' => 'Уникальная выставка работ современных художников',
                'location' => 'Галерея искусств (пр. Культурный, 8)',
                'start_date' => now()->addDays(10),
                'start_time' => '10:00',
                'price' => 500,
                'type' => EventType::CULTURAL,
            ],
        ];

        foreach ($events as $event) {
            Event::create($event);
        }
    }
}
