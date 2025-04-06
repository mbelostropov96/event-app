<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventRegistrationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if we already have registrations
        if (EventRegistration::count() > 0) {
            return;
        }
        
        $users = User::where('role', 'user')->take(5)->get();
        
        // If no users exist, we can't create registrations
        if ($users->isEmpty()) {
            return;
        }
        
        $events = Event::all();
        
        // If no events exist, we can't create registrations
        if ($events->isEmpty()) {
            return;
        }
        
        foreach ($events as $event) {
            // Register some users for each event
            foreach ($users->random(rand(1, min(3, $users->count()))) as $user) {
                EventRegistration::create([
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'registered_at' => Carbon::now()->subDays(rand(1, 10)),
                ]);
            }
        }
    }
}
