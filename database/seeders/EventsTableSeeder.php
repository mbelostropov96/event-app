<?php

namespace Database\Seeders;

use App\Enums\EventType;
use App\Models\Event;
use App\Models\User;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if we already have events
        if (Event::count() > 0) {
            return;
        }
        
        $admin = User::where('role', 'admin')->first();
        
        if (!$admin) {
            // If no admin exists, we can't create events
            return;
        }
        
        // Create upcoming events
        Event::create([
            'title' => 'Фестиваль "Балаковская весна"',
            'description' => 'Ежегодный фестиваль искусств с участием местных артистов, музыкантов и художников.',
            'location' => 'Городской парк',
            'start_date' => now()->addDays(10),
            'start_time' => '12:00',
            'price' => 0,
            'type' => EventType::CULTURAL->value,
            'capacity' => 500,
            'organizer_id' => $admin->id,
        ]);

        Event::create([
            'title' => 'Мастер-класс по живописи',
            'description' => 'Профессиональный художник научит вас основам живописи маслом. Все материалы включены в стоимость.',
            'location' => 'Художественная галерея "Палитра"',
            'start_date' => now()->addDays(5),
            'start_time' => '15:00',
            'price' => 1500,
            'type' => EventType::EDUCATIONAL->value,
            'capacity' => 20,
            'organizer_id' => $admin->id,
        ]);

        Event::create([
            'title' => 'Концерт симфонического оркестра',
            'description' => 'Балаковский симфонический оркестр исполнит произведения классических композиторов.',
            'location' => 'Городской дворец культуры',
            'start_date' => now()->addDays(15),
            'start_time' => '18:30',
            'price' => 800,
            'type' => EventType::ENTERTAINMENT->value,
            'capacity' => 300,
            'organizer_id' => $admin->id,
        ]);

        // Create past events for reviews
        Event::create([
            'title' => 'Выставка современного искусства',
            'description' => 'Экспозиция работ современных художников Балаково и области.',
            'location' => 'Выставочный зал',
            'start_date' => now()->subDays(10),
            'start_time' => '10:00',
            'price' => 400,
            'type' => EventType::CULTURAL->value,
            'capacity' => 100,
            'organizer_id' => $admin->id,
        ]);

        Event::create([
            'title' => 'Городской марафон',
            'description' => 'Ежегодный марафон на 5 и 10 км по улицам города.',
            'location' => 'Центральная площадь',
            'start_date' => now()->subDays(5),
            'start_time' => '09:00',
            'price' => 500,
            'type' => EventType::SPORTS->value,
            'capacity' => 200,
            'organizer_id' => $admin->id,
        ]);
        
        // Create additional random events
        Event::factory(10)->create();
    }
}
