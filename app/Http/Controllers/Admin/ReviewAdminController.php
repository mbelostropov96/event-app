<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EventReview;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ReviewAdminController extends Controller
{
    /**
     * Get all reviews for admin
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $reviews = EventReview::with(['user', 'event'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return response()->json($reviews);
    }

    /**
     * Get a specific review
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $review = EventReview::with(['user', 'event'])
            ->findOrFail($id);
        
        return response()->json($review);
    }

    /**
     * Moderate a review (approve or reject)
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function moderate(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:event_reviews,id',
            'status' => 'required|in:approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $review = EventReview::findOrFail($request->id);
        $review->status = $request->status;
        $review->save();

        return response()->json([
            'message' => 'Review status updated successfully',
            'review' => $review
        ]);
    }

    /**
     * Delete a review
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $review = EventReview::findOrFail($id);
        $review->delete();

        return response()->json([
            'message' => 'Review deleted successfully'
        ]);
    }
}
