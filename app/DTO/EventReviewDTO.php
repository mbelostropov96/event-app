<?php

namespace App\DTO;

use Illuminate\Support\Str;

class EventReviewDTO extends AbstractDTO
{
    public readonly int $id;
    public readonly int $event_id;
    public readonly int $user_id;
    public readonly int $rating;
    public readonly string $comment;
    public readonly string $status;
    public readonly int $moderated_by;
    public readonly string $moderated_at;

    public function __construct(array $data)
    {
        foreach ($data as $key => $datum) {
            $property = Str::camel($key);
            if (property_exists($this, $property)) {
                $this->{$property} = $datum;
            }
        }
    }
}
