<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $event_id
 * @property integer $user_id
 * @property Carbon $registered_at
 *
 * @property Event $event
 */
class EventRegistration extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    public const TABLE = 'event_registrations';

    protected $table = self::TABLE;
    
    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    protected $fillable = [
        'event_id',
        'user_id',
        'registered_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'event_id' => 'integer',
        'user_id' => 'integer',
        'registered_at' => 'date',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(
            Event::class,
            'event_id',
            'id'
        );
    }
}
