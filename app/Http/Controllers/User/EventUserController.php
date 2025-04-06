<?php

namespace App\Http\Controllers\User;

use App\DTO\EventReviewDTO;
use App\Http\Builder\Filters\EventRegistrationFilter as EventRegistrationFilterBuilder;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Contracts\View\View;
use App\Services\EventRegistrationService;
use App\Services\EventReviewService;
use App\Services\EventService;
use App\Models\Event;
use App\Models\EventReview;
use App\Enums\EventReviewStatus;
use Illuminate\Support\Facades\Log;

class EventUserController extends Controller
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly EventReviewService $eventReviewService,
        private readonly EventRegistrationService $eventRegistrationService,
    ) {}

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showEvent(int $id): JsonResponse
    {
        $event = $this->eventService->findOrFail($id);
        $event->load(['reviews.user']);
        
        // Проверяем, зарегистрирован ли текущий пользователь на это событие
        $isRegistered = false;
        
        // Проверяем, может ли пользователь оставить отзыв
        $canReview = false;
        $isPastEvent = $event->start_date < date('Y-m-d');
    
        // Проверяем, аутентифицирован ли пользователь
        // Используем optional() для безопасного доступа к user()
        $user = optional(auth()->user());

        if ($user && $user->id) {
            $userId = $user->id;
            
            // Проверяем напрямую через запрос к базе данных
            $registration = $this->eventRegistrationService->getAll(
                filter: new EventRegistrationFilterBuilder([
                    EventRegistrationFilterBuilder::EVENT_ID => $id,
                    EventRegistrationFilterBuilder::USER_ID => $userId,
                ])
            )->first();
            
            $isRegistered = $registration !== null;
            
            // Проверяем, есть ли у пользователя отзыв на это событие
            $existingReview = $event->reviews->where('user_id', $userId)->first();
            // Пользователь может оставить отзыв, если:
            // 1. Событие прошло
            // 2. Пользователь зарегистрирован на событие
            // 3. У пользователя нет отзыва или его отзыв был отклонен
            $canReview = $isPastEvent && $isRegistered && (!$existingReview || $existingReview->status === 'rejected');
        } else {
            $canReview = false;
            $isRegistered = false;
        }
        
        return response()->json([
            'id' => $event->id,
            'title' => $event->title,
            'description' => $event->description,
            'start_date' => $event->start_date,
            'start_time' => $event->start_time,
            'location' => $event->location,
            'price' => $event->price,
            'type' => $event->type,
            'image' => $event->image,
            'capacity' => $event->capacity,
            'registered_count' => $event->registrations_count,
            'registration_available' => $event->isRegistrationAvailable(),
            'registered' => $isRegistered,
            'can_review' => $canReview,
            'organizer' => $event->organizer ? [
                'id' => $event->organizer->id,
                'name' => $event->organizer->name
            ] : null
        ]);
    }

    /**
     * @return View
     */
    public function indexEvents(): View
    {
        $events = $this->eventService->getAll();

        return view('events.index', compact('events'));
    }

    /**
     * @param int $id
     * @return View
     */
    public function showEventRegistration(int $id): View
    {
        $eventRegistration = $this->eventRegistrationService->findOrFail($id);

        return view('', compact('eventRegistration'));
    }

    /**
     * @return View
     */
    public function indexEventRegistrations(): View
    {
        $filter = new EventRegistrationFilterBuilder([
            EventRegistrationFilterBuilder::USER_ID => auth()->user()->id,
        ]);

        $eventRegistrations = $this->eventRegistrationService->getAll(filter: $filter);

        return view('', compact('eventRegistrations'));
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function registerForEvent(int $id): JsonResponse
    {
        try {
            $result = $this->eventRegistrationService->register($id);
            return response()->json([
                'message' => 'Successfully registered for event',
                'registration' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function unregisterForEvent(int $id): JsonResponse
    {
        try {
            $result = $this->eventRegistrationService->unregister($id);
            return response()->json([
                'message' => 'Successfully unregistered from event'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function storeEventReview(int $id, Request $request): JsonResponse
    {
        // Validate the request manually
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'rating' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $eventReviewDto = new EventReviewDTO([
            'eventId' => $id,
            'userId' => auth()->user()->id,
            'status' => 'pending',
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        try {
            $result = $this->eventReviewService->create($eventReviewDto);
            
            return response()->json([
                'message' => 'Review submitted successfully',
                'review' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(Request $request): JsonResponse
    {
        $query = Event::query();

        // По умолчанию скрываем прошедшие события, если не указан параметр show_past_events
        $showPastEvents = $request->boolean('show_past_events', false);
        if (!$showPastEvents) {
            $query->where('start_date', '>=', now()->startOfDay());
        }

        if ($request->has('from_date')) {
            $query->where('start_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('start_date', '<=', $request->to_date);
        }

        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        if ($request->has('price_range') && !$request->has('price_min') && !$request->has('price_max')) {
            if ($request->price_range === 'free') {
                $query->where(function($q) {
                    $q->where('price', 0)->orWhereNull('price');
                });
            } else if ($request->price_range === '4000+') {
                $query->where('price', '>=', 4000);
            } else {
                $range = explode('-', $request->price_range);
                if (count($range) === 2) {
                    $min = (int)$range[0];
                    $max = (int)$range[1];
                    $query->whereBetween('price', [$min, $max]);
                }
            }
        }
        
        // Handle free events
        if ($request->has('price_max') && $request->price_max == 0) {
            $query->where(function($q) {
                $q->where('price', 0)->orWhereNull('price');
            });
        }

        // Filter upcoming events
        if ($request->boolean('upcoming')) {
            $query->where('start_date', '>=', now()->startOfDay())
                  ->orderBy('start_date', 'asc');
            
            // For upcoming events on home page, we only need 5
            if ($request->has('limit')) {
                $query->limit($request->integer('limit'));
                $events = $query->get();
                return response()->json($events);
            }
        }

        // Sorting
        $sortBy = $request->input('sort', 'start_date');
        $sortDir = $request->input('sort_dir', 'asc');
        $query->orderBy($sortBy, $sortDir);

        // Pagination
        $perPage = $request->input('per_page', 12);
        $events = $query->paginate($perPage);

        return response()->json($events);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getEventReviews(int $id): JsonResponse
    {
        $event = $this->eventService->findOrFail($id);
        
        $reviews = EventReview::with(['user'])
            ->where('event_id', $id)
            ->where('status', EventReviewStatus::APPROVED->value)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $formattedReviews = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user' => [
                    'id' => $review->user->id,
                    'first_name' => $review->user->first_name,
                    'last_name' => $review->user->last_name,
                ],
                'rating' => $review->rating,
                'comment' => $review->comment,
                'created_at' => $review->created_at->format('Y-m-d H:i:s'),
            ];
        });
            
        return response()->json([
            'data' => $formattedReviews,
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total()
            ]
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function getEvent(int $id): JsonResponse
    {
        $event = $this->eventService->findOrFail($id);
        $event->load(['reviews.user']);

        // Проверяем, может ли пользователь оставить отзыв
        $canReview = false;
        $isPastEvent = $event->start_date < date('Y-m-d');
        $isRegistered = false;
        
        // Используем optional() для безопасного доступа к user()
        $user = optional(auth()->user());
        if ($user && $user->id) {
            $userId = $user->id;
            // Проверяем напрямую через запрос к базе данных
            $registration = $this->eventRegistrationService->getAll(
                filter: new EventRegistrationFilterBuilder([
                    EventRegistrationFilterBuilder::EVENT_ID => $id,
                    EventRegistrationFilterBuilder::USER_ID => $userId,
                ])
            )->first();
            
            $isRegistered = $registration !== null;
            
            // Проверяем, есть ли у пользователя отзыв на это событие
            $existingReview = $event->reviews->where('user_id', $userId)->first();
            // Пользователь может оставить отзыв, если:
            // 1. Событие прошло
            // 2. Пользователь зарегистрирован на событие
            // 3. У пользователя нет отзыва или его отзыв был отклонен
            $canReview = $isPastEvent && $isRegistered && (!$existingReview || $existingReview->status === 'rejected');
        }

        return response()->json(array_merge($event->toArray(), [
            'can_review' => $canReview,
            'registered' => $isRegistered
        ]));
    }
}
