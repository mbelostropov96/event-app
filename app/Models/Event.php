<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
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
 * @property integer $price
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
    ];

    protected $casts = [
        'id' => 'integer',
        'title' => 'string',
        'description' => 'string',
        'location' => 'string',
        'start_date' => 'date:Y-m-d',
        'start_time' => 'datetime:H:i',
        'price' => 'integer',
    ];

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(
            EventRegistration::class,
            'event_id',
            'id'
        );
    }
}
