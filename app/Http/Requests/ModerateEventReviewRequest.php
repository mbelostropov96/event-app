<?php

namespace App\Http\Requests;

use App\Enums\EventReviewStatus;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rule;

class ModerateEventReviewRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => ['required', 'integer', 'min:1',],
            'status' => [
                'required',
                'string',
                Rule::enum(EventReviewStatus::class),
            ],
        ];
    }
}
