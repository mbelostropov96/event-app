<?php

namespace App\Services;

use App\DTO\EventReviewDTO;
use App\Enums\EventReviewStatus;
use App\Http\Builder\Filters\EventRegistrationFilter;
use App\Http\Builder\Filters\EventReviewFilter;
use App\Http\Builder\Sorters\EventReviewSorter;
use App\Models\EventRegistration;
use App\Models\EventReview;
use Illuminate\Database\Eloquent\Collection;
use RuntimeException;
use Symfony\Component\HttpFoundation\Response;

class EventReviewService
{
    public function __construct(
        private readonly EventRegistrationService $eventRegistrationService,
    ) {}

    /**
     * @param int $id
     * @return EventReview|null
     */
    public function findById(int $id): ?EventReview
    {
        $filter = new EventReviewFilter([
            EventReviewFilter::ID => $id,
        ]);

        return $this->getAll(filter: $filter)
            ->first();
    }

    /**
     * @param int $id
     * @return EventReview
     */
    public function findOrFail(int $id): EventReview
    {
        $eventReview = $this->findById($id);

        if ($eventReview === null) {
            throw new RuntimeException('Review not found', Response::HTTP_BAD_REQUEST);
        }

        return $eventReview;
    }

    /**
     * @param array|null $relations
     * @param EventReviewFilter|null $filter
     * @param EventReviewSorter|null $sorter
     * @return Collection<EventReview>
     */
    public function getAll(
        array $relations = null,
        EventReviewFilter $filter = null,
        EventReviewSorter $sorter = null
    ): Collection
    {
        $builder = (new EventReview())->newQuery();

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
     * @param EventReviewDTO $eventReviewDto
     * @return EventReview
     */
    public function create(EventReviewDTO $eventReviewDto): EventReview
    {
        // Сначала проверяем, зарегистрирован ли пользователь на мероприятие
        $eventRegistration = $this->eventRegistrationService->getAll(
            ['event'],
            new EventRegistrationFilter([
                EventRegistrationFilter::EVENT_ID => $eventReviewDto->eventId,
                EventRegistrationFilter::USER_ID => $eventReviewDto->userId,
            ])
        )->first();

        if ($eventRegistration === null) {
            throw new RuntimeException('Вы не зарегистрированы на это мероприятие. Отзывы могут оставлять только зарегистрированные участники.', Response::HTTP_BAD_REQUEST);
        }

        $event = $eventRegistration->event;

        if ($event === null) {
            throw new RuntimeException('Event not found', Response::HTTP_BAD_REQUEST);
        }

        if ($event->start_date > date('Y-m-d')) {
            throw new RuntimeException('Нельзя оставить отзыв на мероприятие, которое еще не состоялось', Response::HTTP_BAD_REQUEST);
        }

        $existingReview = $this->getAll(filter: new EventReviewFilter([
            EventReviewFilter::EVENT_ID => $eventReviewDto->eventId,
            EventReviewFilter::USER_ID => $eventReviewDto->userId,
        ]))
            ->first();

        if ($existingReview !== null && $existingReview->status !== 'rejected') {
            throw new RuntimeException('Вы уже оставили отзыв на это мероприятие', Response::HTTP_BAD_REQUEST);
        }

        /** @var EventReview $eventReview */
        $eventReview = (new EventReview())->newQuery()
            ->create($eventReviewDto->toArray());

        return $eventReview;
    }

    /**
     * @param int $eventReviewId
     * @param string $status
     * @return bool
     */
    public function moderate(int $eventReviewId, string $status): bool
    {
        $eventReview = $this->findOrFail($eventReviewId);

        if (!in_array($status, EventReviewStatus::cases())) {
            throw new RuntimeException('Неверный статус отзыва', Response::HTTP_BAD_REQUEST);
        }

        return $eventReview->update([
           'status' => $status,
           'moderated_by' => auth()->user()->id,
           'moderated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
