<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;

class StoreEventRequest extends AbstractRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255',],
            'description' => ['nullable', 'string',],
            'location' => ['required', 'string', 'max:255',],
            'date' => ['required', 'date_format:Y-m-d',],
            'start_time' => ['required', 'date_format:H:i',],
            'price' => ['nullable', 'integer',],
        ];
    }
}
