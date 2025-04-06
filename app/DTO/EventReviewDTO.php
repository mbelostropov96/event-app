<?php

namespace App\DTO;

use Illuminate\Support\Str;

class EventReviewDTO extends AbstractDTO
{
    public readonly ?int $id;
    public readonly int $eventId;
    public readonly int $userId;
    public readonly int $rating;
    public readonly ?string $comment;
    public readonly string $status;
    public readonly ?int $moderatedBy;
    public readonly ?string $moderated_at;

    public function __construct(array $data)
    {
        // Проверяем наличие обязательных полей
        $requiredFields = ['eventId', 'userId', 'rating', 'status'];
        foreach ($requiredFields as $field) {
            if (!isset($data[$field])) {
                throw new \InvalidArgumentException("Field '{$field}' is required for EventReviewDTO");
            }
        }
     
        foreach ($data as $key => $value) {
            $property = Str::camel($key);
            if (property_exists($this, $property)) {
                $this->{$property} = $value;
            }
        }
    }
    
    /**
     * Convert DTO to array for model creation
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'event_id' => $this->eventId,
            'user_id' => $this->userId,
            'rating' => $this->rating,
            'comment' => $this->comment,
            'status' => $this->status,
        ];
    }
}
