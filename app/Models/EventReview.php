<?php

namespace App\Models;

use App\Models\Event;
use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property integer $id
 * @property integer $event_id
 * @property integer $user_id
 * @property integer $rating
 * @property string $comment
 * @property string $status
 * @property integer $moderated_by
 * @property Carbon $moderated_at
 *
 * @property Event $event
 * @property User $user
 * @property User $moderator
 */
class EventReview extends Model
{
    use HasFactory;
    use Filterable;
    use Sortable;

    public const TABLE = 'event_reviews';

    protected $table = self::TABLE;

    protected $fillable = [
        'event_id',
        'user_id',
        'rating',
        'comment',
        'status',
        'moderated_by',
        'moderated_at',
    ];

    protected $casts = [
        'id' => 'integer',
        'event_id' => 'integer',
        'user_id' => 'integer',
        'rating' => 'integer',
        'comment' => 'string',
        'status' => 'string',
        'moderated_by' => 'integer',
        'moderated_at' => 'date',
    ];

    /**
     * Get the event that the review belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user that wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who moderated the review
     */
    public function moderator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'moderated_by');
    }
}
