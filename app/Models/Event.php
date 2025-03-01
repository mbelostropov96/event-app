<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use App\Enums\EventType;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property integer $id
 * @property string $title
 * @property string $description
 * @property string $location
 * @property Carbon $start_date
 * @property Carbon $start_time
 * @property float $price
 * @property EventType $type
 * @property integer $capacity
 * @property integer $registrations_count
 *
 * @property Collection<EventRegistration> $eventRegistrations
 */
class Event extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    public const TABLE = 'events';

    protected $table = self::TABLE;

    protected $fillable = [
        'title',
        'description',
        'location',
        'start_date',
        'start_time',
        'price',
        'type',
        'capacity',
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'location' => 'string',
        'start_date' => 'date:Y-m-d',
        'start_time' => 'datetime:H:i',
        'price' => 'integer',
        'type' => EventType::class,
        'capacity' => 'integer',
    ];

    /**
     * Check if registration is available for this event
     * 
     * @return bool
     */
    public function isRegistrationAvailable(): bool
    {
        // If event has no capacity limit
        if (!$this->capacity) {
            return true;
        }

        // If event date has passed
        if ($this->start_date < now()->startOfDay()) {
            return false;
        }

        // Check if there are available spots
        return $this->registrations_count < $this->capacity;
    }

    /**
     * Get the registrations for the event
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    /**
     * Get the organizer of the event
     */
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(
            EventRegistration::class,
            'event_id',
            'id'
        );
    }
}
