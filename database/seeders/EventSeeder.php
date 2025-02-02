<?php

namespace Database\Seeders;

use App\Models\Event;
use Illuminate\Database\Seeder;

class EventSeeder extends Seeder
{
    public function run(): void
    {
        // Создаем обычные события
        Event::factory()
            ->count(20)
            ->create();

        // Создаем бесплатные события
        Event::factory()
            ->count(5)
            ->free()
            ->create();

        // Создаем премиум события
        Event::factory()
            ->count(5)
            ->premium()
            ->create();
    }
}
