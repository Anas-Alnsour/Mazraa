<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreFarmRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Only allow users with the 'farm_owner' role
        return Auth::check() && Auth::user()->role === 'farm_owner';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'                      => ['required', 'string', 'max:255'],
            'description'               => ['required', 'string'],
            'governorate'               => ['required', 'string', Rule::in(config('mazraa.governorates'))],
            'location'                  => ['required', 'string', 'max:255'],
            'location_link'             => ['nullable', 'url', 'max:255'],
            'capacity'                  => ['required', 'integer', 'min:1'],
            'price_per_morning_shift'   => ['required', 'numeric', 'min:0'],
            'price_per_evening_shift'   => ['required', 'numeric', 'min:0'],
            'price_per_full_day'        => ['required', 'numeric', 'min:0'],
            'main_image'                => ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'gallery'                   => ['nullable', 'array'],
            'gallery.*'                 => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'main_image.required' => 'The main farm cover image is required.',
            'main_image.mimes'    => 'The main image must be a file of type: jpg, jpeg, png, webp.',
            'gallery.*.mimes'     => 'Each gallery image must be a file of type: jpg, jpeg, png, webp.',
            'location_link.url'   => 'The location link must be a valid URL (e.g., Google Maps link).',
        ];
    }
}
