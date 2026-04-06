<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreFarmBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only authenticated users with the 'user' (Customer) role can book
        return Auth::check() && Auth::user()->role === 'user';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'farm_id' => 'required|exists:farms,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'shift' => 'required|string|in:morning,evening,full_day',

            // Transport Logic
            'requires_transport' => 'nullable|boolean',
            'pickup_lat' => 'required_if:requires_transport,true|nullable|numeric',
            'pickup_lng' => 'required_if:requires_transport,true|nullable|numeric',
            'passengers' => 'required_if:requires_transport,true|nullable|integer|min:1',

            // Cart/Supplies Logic
            'supplies' => 'nullable|array',
            'supplies.*.id' => 'required_with:supplies|exists:supplies,id',
            'supplies.*.quantity' => 'required_with:supplies|integer|min:1',
        ];
    }

    public function messages(): array
    {
        return [
            'pickup_lat.required_if' => 'Please drop a pin on the map for your transport pickup location.',
            'pickup_lng.required_if' => 'Please drop a pin on the map for your transport pickup location.',
            'passengers.required_if' => 'Please specify the number of passengers for the transport.',
        ];
    }
}
