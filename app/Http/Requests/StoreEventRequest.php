<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreEventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => 'required|string',
            'description'    => 'nullable|string',
            'location'       => 'required|string',
            'event_datetime' => 'required|date|after:now',
            'total_seats'    => 'required|integer|min:1',
        ];
    }
}
