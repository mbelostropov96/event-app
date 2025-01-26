<?php

namespace App\Helpers;

use App\Enums\UserRole;

class UserHelper
{
    /**
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return optional(auth()->user())->role === UserRole::ADMIN->value;
    }
}
