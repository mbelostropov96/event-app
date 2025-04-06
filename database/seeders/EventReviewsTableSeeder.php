<?php

namespace Database\Seeders;

use App\Enums\EventReviewStatus;
use App\Models\Event;
use App\Models\EventRegistration;
use App\Models\EventReview;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventReviewsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Skip if we already have reviews
        if (EventReview::count() > 0) {
            return;
        }
        
        // Get past events
        $pastEvents = Event::where('start_date', '<', now())->get();
        
        // If no past events exist, we can't create reviews
        if ($pastEvents->isEmpty()) {
            return;
        }
        
        $admin = User::where('role', 'admin')->first();
        
        // If no admin exists, we can't properly moderate reviews
        if (!$admin) {
            return;
        }
        
        foreach ($pastEvents as $event) {
            // Get registrations for this event
            $registrations = EventRegistration::where('event_id', $event->id)->get();
            
            // If no registrations exist for this event, skip it
            if ($registrations->isEmpty()) {
                continue;
            }
            
            foreach ($registrations as $registration) {
                // Check if review already exists
                if (EventReview::where('event_id', $event->id)->where('user_id', $registration->user_id)->exists()) {
                    continue;
                }
                
                // 70% chance to leave a review
                if (rand(1, 10) <= 7) {
                    $status = rand(1, 10) <= 8 ? EventReviewStatus::APPROVED : EventReviewStatus::PENDING;
                    
                    $review = EventReview::create([
                        'event_id' => $event->id,
                        'user_id' => $registration->user_id,
                        'rating' => rand(3, 5), // Mostly positive reviews
                        'comment' => $this->getRandomComment(),
                        'status' => $status->value,
                        'created_at' => Carbon::now()->subDays(rand(1, 5)),
                    ]);
                    
                    // If review is approved, set moderation data
                    if ($status === EventReviewStatus::APPROVED) {
                        $review->update([
                            'moderated_by' => $admin->id,
                            'moderated_at' => Carbon::now()->subDays(rand(0, 3)),
                        ]);
                    }
                }
            }
        }
    }
    
    /**
     * Get a random review comment
     * 
     * @return string
     */
    private function getRandomComment(): string
    {
        $comments = [
            'Отличное мероприятие! Всё было организовано на высшем уровне.',
            'Мне очень понравилось. Обязательно приду ещё раз.',
            'Хорошая организация, но можно было бы улучшить звук.',
            'Интересное событие, но немного затянуто по времени.',
            'Прекрасная атмосфера и дружелюбный персонал.',
            'Всё было супер! Рекомендую всем!',
            'Хорошее мероприятие для всей семьи.',
            'Отличный способ провести выходные.',
            'Было интересно, но ожидал большего.',
            'Организаторы молодцы, всё прошло гладко.',
        ];
        
        return $comments[array_rand($comments)];
    }
}
