<?php

namespace App\Http\Requests;

use App\Enums\EventReviewRating;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class StoreEventReviewRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'event_id' => ['required', 'integer', 'min:1',],
            'rating' => [
                'required',
                'integer',
                Rule::enum(EventReviewRating::class),
            ],
            'comment' => ['nullable', 'string'],
        ];
    }
}
