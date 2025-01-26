<?php

namespace App\Http\Controllers\Admin;

use App\DTO\EventDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\ModerateEventReviewRequest;
use App\Http\Requests\StoreEventRequest;
use App\Services\EventReviewService;
use App\Services\EventService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class EventAdminController extends Controller
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly EventReviewService $eventReviewService,
    ) {}

    /**
     * @param int $id
     * @return View
     */
    public function showEvent(int $id): View
    {
        $event = $this->eventService->findOrFail($id);

        return view('', compact('event'));
    }

    /**
     * @return View
    */
    public function indexEvents(): View
    {
        $events = $this->eventService->getAll();

        return view('', compact('events'));
    }

    /**
     * @param StoreEventRequest $request
     * @return RedirectResponse
     */
    public function storeEvent(StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $this->eventService->create($data);

        return redirect()->to(route(''));
    }

    /**
     * @param int $id
     * @param StoreEventRequest $request
     * @return RedirectResponse
     */
    public function updateEvent(int $id, StoreEventRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $eventDto = new EventDto(array_merge($data, [
            'id' => $id,
        ]));

        $this->eventService->update($eventDto);

        return redirect()->to(route(''));
    }

    /**
     * @param int $id
     * @return RedirectResponse
     */
    public function destroyEvent(int $id): RedirectResponse
    {
        $this->eventService->delete($id);

        return redirect()->to(route(''));
    }

    /**
     * @param int $id
     * @return View
     */
    public function showEventReview(int $id): View
    {
        $eventReview = $this->eventReviewService->findOrFail($id);

        return view('', compact('eventReview'));
    }

    /**
     * @return View
     */
    public function indexEventReviews(): View
    {
        $eventReviews = $this->eventReviewService->getAll();

        return view('', compact('eventReviews'));
    }

    /**
     * @param ModerateEventReviewRequest $request
     * @return RedirectResponse
     */
    public function moderateEventReview(ModerateEventReviewRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $eventReviewId = $data['id'];
        $status = $data['status'];

        $this->eventReviewService->moderate($eventReviewId, $status);

        return redirect()->to(route(''));
    }
}
