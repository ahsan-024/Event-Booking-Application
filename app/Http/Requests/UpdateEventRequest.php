<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => 'sometimes|string',
            'description'    => 'nullable|string',
            'location'       => 'sometimes|string',
            'event_datetime' => 'sometimes|date|after:now',
            'total_seats'    => 'sometimes|integer|min:1',
        ];
    }
}
