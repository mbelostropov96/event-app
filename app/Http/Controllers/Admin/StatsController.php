<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventReview;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StatsController extends Controller
{
    /**
     * Get admin dashboard statistics
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $stats = [
            'events' => Event::count(),
            'users' => User::count(),
            'reviews' => EventReview::count(),
            'pending_reviews' => EventReview::where('status', 'pending')->count(),
        ];
        
        return response()->json($stats);
    }
}
