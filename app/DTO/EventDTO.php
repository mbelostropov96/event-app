<?php

namespace App\DTO;

use Illuminate\Support\Str;

class EventDTO extends AbstractDTO
{
    public readonly int $id;
    public readonly string $title;
    public readonly string $description;
    public readonly string $location;
    public readonly string $startDate;
    public readonly string $startTime;
    public readonly int $price;
    public readonly ?string $image;

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
