<?php

namespace App\Http\Controllers\User;

use App\DTO\EventReviewDTO;
use App\Http\Builder\Filters\EventRegistrationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEventReviewRequest;
use App\Services\EventRegistrationService;
use App\Services\EventReviewService;
use App\Services\EventService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Event;

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
        $filter = new EventRegistrationFilter([
            EventRegistrationFilter::USER_ID => auth()->user()->id,
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
     * @param StoreEventReviewRequest $request
     * @return View
     */
    public function storeEventReview(StoreEventReviewRequest $request): View
    {
        $data = $request->validated();

        $eventReviewDto = new EventReviewDTO(array_merge($data, [
            'user_id' => auth()->user()->id,
        ]));

        $result = $this->eventReviewService->create($eventReviewDto);

        return view('', compact('result'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getEvents(Request $request): JsonResponse
    {
        $query = Event::query();

        // Filter by date range
        if ($request->has('from_date')) {
            $query->where('start_date', '>=', $request->from_date);
        }
        if ($request->has('to_date')) {
            $query->where('start_date', '<=', $request->to_date);
        }

        // Filter by type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filter by price
        if ($request->has('price_min')) {
            $query->where('price', '>=', $request->price_min);
        }
        if ($request->has('price_max')) {
            $query->where('price', '<=', $request->price_max);
        }
        
        // Handle price_range parameter if it exists
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
}
