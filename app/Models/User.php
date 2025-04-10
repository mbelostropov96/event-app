<?php

namespace App\Models;

use App\Models\Traits\Filterable;
use App\Models\Traits\Sortable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property integer $id
 * @property string $first_name
 * @property string $middle_name
 * @property string $last_name
 * @property string $email
 * @property string $password
 * @property string $role
 * @property string $remember_token
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * 
 * @property Collection<EventRegistration> $eventRegistrations
 * @property Collection<EventReview> $reviews
 */
class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use Filterable;
    use Sortable;

    public const TABLE = 'users';

    protected $table = self::TABLE;

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'password',
        'role',
        'remember_token',
        'created_at',
        'updated_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'id' => 'integer',
        'first_name' => 'string',
        'middle_name' => 'string',
        'last_name' => 'string',
        'email' => 'string',
        'password' => 'hashed',
        'role' => 'string',
        'remember_token' => 'string',
        'created_at' => 'date',
        'updated_at' => 'date',
    ];
    
    /**
     * Get the user's full name
     * 
     * @return string
     */
    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->middle_name} {$this->last_name}";
    }
    
    /**
     * Get the user's event registrations
     */
    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }
    
    /**
     * Get the user's event reviews
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(EventReview::class);
    }
}
