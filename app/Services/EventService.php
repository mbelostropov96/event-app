<?php

namespace App\Services;

use App\DTO\EventDTO;
use App\Http\Builder\Filters\EventFilter;
use App\Http\Builder\Sorters\EventSorter;
use App\Models\Event;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class EventService
{
    /**
     * @param int $id
     * @return Event|null
     */
    public function findById(int $id): ?Event
    {
        $filter = new EventFilter([
            EventFilter::ID => $id,
        ]);

        return $this->getAll(filter: $filter)
            ->first();
    }

    /**
     * @param int $id
     * @return Event
     */
    public function findOrFail(int $id): Event
    {
        $event = $this->findById($id);

        if ($event === null) {
            throw new RuntimeException('Event not found', Response::HTTP_BAD_REQUEST);
        }

        return $event;
    }

    /**
     * @param array|null $relations
     * @param EventFilter|null $filter
     * @param EventSorter|null $sorter
     * @return Collection
     */
    public function getAll(array $relations = null, EventFilter $filter = null, EventSorter $sorter = null): Collection
    {
        $builder = (new Event())->newQuery();

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
     * @param EventDTO $eventDTO
     * @return Event
     */
    public function create(EventDTO $eventDTO): Event
    {
        /** @var Event $event */
        $event = (new Event())->newQuery()
            ->create($eventDTO->toArray());

        return $event;
    }

    /**
     * @param EventDTO $eventDTO
     * @return Event
     */
    public function update(EventDTO $eventDTO): Event
    {
        $eventToUpdate = $this->findOrFail($eventDTO->id);

        $eventToUpdate->update($eventDTO->toArray());

        return $eventToUpdate;
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $eventToUpdate = $this->findOrFail($id);

        return (bool)$eventToUpdate->delete();
    }
}
