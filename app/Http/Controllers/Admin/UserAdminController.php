<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\EventRegistration;
use App\Models\EventReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserAdminController extends Controller
{
    /**
     * Get all users
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = User::all();
        
        return response()->json($users);
    }

    /**
     * Get user by ID
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        
        return response()->json($user);
    }

    /**
     * Get user statistics
     *
     * @param int $id
     * @return JsonResponse
     */
    public function getUserStats(int $id): JsonResponse
    {
        $user = User::findOrFail($id);
        
        // Get registrations count
        $registrationsCount = EventRegistration::where('user_id', $id)->count();
        
        // Get reviews count and average rating
        $reviews = EventReview::where('user_id', $id)->get();
        $reviewsCount = $reviews->count();
        $averageRating = $reviewsCount > 0 ? $reviews->avg('rating') : 0;
        
        return response()->json([
            'registrations_count' => $registrationsCount,
            'reviews_count' => $reviewsCount,
            'average_rating' => $averageRating,
        ]);
    }
}
