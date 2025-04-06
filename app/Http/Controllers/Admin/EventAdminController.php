<?php

namespace App\Http\Controllers\Admin;

use App\DTO\EventDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModerateEventReviewRequest;
use App\Http\Requests\StoreEventRequest;
use App\Services\EventReviewService;
use App\Services\EventService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventAdminController extends Controller
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly EventReviewService $eventReviewService,
    ) {}

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showEvent(int $id): JsonResponse
    {
        $event = $this->eventService->findOrFail($id);

        return response()->json($event);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function indexEvents(Request $request): JsonResponse
    {
        $upcoming = $request->query('upcoming', false);
        $limit = $request->query('limit', null);
        
        $events = $this->eventService->getAll();
        
        if ($upcoming) {
            $events = $events->filter(function($event) {
                return $event->start_date >= date('Y-m-d');
            });
        }
        
        if ($limit) {
            $events = $events->take($limit);
        }

        return response()->json([
            'data' => $events->values(),
            'meta' => [
                'total' => $events->count(),
                'last_page' => 1
            ]
        ]);
    }

    /**
     * @param StoreEventRequest $request
     * @return JsonResponse
     */
    public function storeEvent(StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();

        $eventDto = new EventDTO($data);
        $event = $this->eventService->create($eventDto);

        return response()->json([
            'message' => 'Event created successfully',
            'event' => $event
        ], 201);
    }

    /**
     * @param int $id
     * @param StoreEventRequest $request
     * @return JsonResponse
     */
    public function updateEvent(int $id, StoreEventRequest $request): JsonResponse
    {
        $data = $request->validated();

        $eventDto = new EventDTO(array_merge($data, [
            'id' => $id,
        ]));

        $event = $this->eventService->update($eventDto);

        return response()->json([
            'message' => 'Event updated successfully',
            'event' => $event
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function destroyEvent(int $id): JsonResponse
    {
        $this->eventService->delete($id);

        return response()->json([
            'message' => 'Event deleted successfully'
        ]);
    }

    /**
     * @param int $id
     * @return JsonResponse
     */
    public function showEventReview(int $id): JsonResponse
    {
        $eventReview = $this->eventReviewService->findOrFail($id);

        return response()->json($eventReview);
    }

    /**
     * @return JsonResponse
     */
    public function indexEventReviews(): JsonResponse
    {
        $eventReviews = $this->eventReviewService->getAll();

        return response()->json($eventReviews);
    }

    /**
     * @param ModerateEventReviewRequest $request
     * @return JsonResponse
     */
    public function moderateEventReview(ModerateEventReviewRequest $request): JsonResponse
    {
        $data = $request->validated();

        $eventReviewId = $data['id'];
        $status = $data['status'];

        $this->eventReviewService->moderate($eventReviewId, $status);

        return response()->json([
            'message' => 'Event review moderated successfully'
        ]);
    }
}
