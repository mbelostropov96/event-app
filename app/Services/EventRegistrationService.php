<?php

namespace App\Services;

use App\Http\Builder\Filters\EventRegistrationFilter;
use App\Http\Builder\Sorters\EventRegistrationSorter;
use App\Models\EventRegistration;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class EventRegistrationService
{
    public function __construct(
        private readonly EventService $eventService,
    ) {}

    /**
     * @param int $id
     * @return EventRegistration|null
     */
    public function findById(int $id): ?EventRegistration
    {
        $filter = new EventRegistrationFilter([
            EventRegistrationFilter::ID => $id,
        ]);

        return $this->getAll(filter: $filter)
            ->first();
    }

    /**
     * @param int $id
     * @return EventRegistration
     */
    public function findOrFail(int $id): EventRegistration
    {
        $eventRegistration = $this->findById($id);

        if ($eventRegistration === null) {
            throw new RuntimeException('Registration not found', Response::HTTP_BAD_REQUEST);
        }

        return $eventRegistration;
    }

    /**
     * @param array|null $relations
     * @param EventRegistrationFilter|null $filter
     * @param EventRegistrationSorter|null $sorter
     * @return Collection
     */
    public function getAll(
        array $relations = null,
        EventRegistrationFilter $filter = null,
        EventRegistrationSorter $sorter = null
    ): Collection
    {
        $builder = (new EventRegistration())->newQuery();

        if ($relations !== null) {
            $builder->with($relations);
        }
        if ($filter !== null) {
            $builder->filter($filter);
        }
        if ($sorter !== null) {
            $builder->sorter($sorter);
        }

        return $builder->get();
    }

    /**
     * @param int $eventId
     * @param int|null $userId
     * @return bool
     */
    public function register(int $eventId, int $userId = null): bool
    {
        if ($userId === null) {
            $userId = auth()->user()->id;
        }

        $event = $this->eventService->findOrFail($eventId);

        if ($event->start_date > date('Y-m-d', time())) {
            throw new RuntimeException('loh', Response::HTTP_BAD_REQUEST);
        }

        /** @var EventRegistration $eventUser */
        $eventUser = (new EventRegistration())->newQuery()
            ->create([
                'event_id' => $event->id,
                'user_id' => $userId,
                'registered_at' => date('Y-m-d', time()),
            ]);

        return !($eventUser->id === null);
    }

    /**
     * @param int $eventId
     * @param int|null $userId
     * @return bool
     */
    public function unregister(int $eventId, int $userId = null): bool
    {
        if ($userId === null) {
            $userId = auth()->user()->id;
        }

        $filter = new EventRegistrationFilter([
            EventRegistrationFilter::EVENT_ID => $eventId,
            EventRegistrationFilter::USER_ID => $userId,
        ]);

        /** @var EventRegistration $eventRegistration */
        $eventRegistration = $this->getAll(['event'], $filter)
            ->firstOrFail();

        if ($eventRegistration->event === null) {
            throw new RuntimeException('loh', Response::HTTP_BAD_REQUEST);
        }

        if ($eventRegistration->event->start_date > date('Y-m-d', time())) {
            throw new RuntimeException('loh', Response::HTTP_BAD_REQUEST);
        }

        return (bool)$eventRegistration;
    }
}
