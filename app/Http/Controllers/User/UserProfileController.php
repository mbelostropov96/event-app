<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\EventRegistration;
use App\Models\EventReview;
use App\Services\EventRegistrationService;
use App\Services\EventReviewService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function __construct(
        private readonly EventRegistrationService $eventRegistrationService,
        private readonly EventReviewService $eventReviewService,
    ) {}

    /**
     * Get user's event registrations
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getRegistrations(Request $request): JsonResponse
    {
        $registrations = EventRegistration::with('event')
            ->where('user_id', $request->user()->id)
            ->orderBy('registered_at', 'desc')
            ->get()
            ->map(function ($registration) {
                return [
                    'id' => $registration->id,
                    'event' => [
                        'id' => $registration->event->id,
                        'title' => $registration->event->title,
                        'start_date' => $registration->event->start_date,
                        'start_time' => $registration->event->start_time,
                        'location' => $registration->event->location,
                        'image' => $registration->event->image,
                    ],
                    'registered_at' => $registration->registered_at,
                ];
            });

        return response()->json($registrations);
    }

    /**
     * Get user's event reviews
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getReviews(Request $request): JsonResponse
    {
        $reviews = EventReview::with(['event', 'user'])
            ->where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        $formattedReviews = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'user' => [
                    'id' => $review->user->id,
                    'first_name' => $review->user->first_name,
                    'last_name' => $review->user->last_name,
                ],
                'event' => [
                    'id' => $review->event->id,
                    'title' => $review->event->title,
                    'image' => $review->event->image,
                ],
                'rating' => $review->rating,
                'comment' => $review->comment,
                'status' => $review->status,
                'created_at' => $review->created_at->format('Y-m-d H:i:s'),
            ];
        });
            
        return response()->json([
            'data' => $formattedReviews,
            'meta' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total()
            ]
        ]);
    }

    /**
     * Delete user's event review
     *
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function deleteReview(int $id, Request $request): JsonResponse
    {
        $review = EventReview::where('id', $id)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $review->delete();

        return response()->json([
            'message' => 'Отзыв успешно удален',
        ]);
    }
}
