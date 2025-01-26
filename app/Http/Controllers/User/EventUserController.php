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

class EventUserController extends Controller
{
    public function __construct(
        private readonly EventService $eventService,
        private readonly EventReviewService $eventReviewService,
        private readonly EventRegistrationService $eventRegistrationService,
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
     * @return View
     */
    public function registerForEvent(): View
    {
        $eventId = request()->input('eventId');

        $result = $this->eventRegistrationService->register($eventId);

        return view('', compact('result'));
    }

    /**
     * @return View
     */
    public function unregisterForEvent(): View
    {
        $eventId = request()->input('eventId');

        $result = $this->eventRegistrationService->unregister($eventId);

        return view('', compact('result'));
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
}
