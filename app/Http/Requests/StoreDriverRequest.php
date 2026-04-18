<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class StoreDriverRequest extends FormRequest
{
    public function authorize(): bool
    {
        // حمايتك الأمنية الممتازة
        return Auth::check() && in_array(Auth::user()->role, ['supply_company', 'transport_company']);
    }

    public function rules(): array
    {
        $rules = [
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users,email',
            'phone'    => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'shift'    => ['required', 'string', Rule::in(['morning', 'evening'/*, 'full_day'*/])],
        ];

        if (Auth::user()->role === 'transport_company') {
            $rules['governorate'] = ['required', 'string', Rule::in(config('mazraa.governorates'))];
            $rules['transport_vehicle_id'] = 'nullable|exists:vehicles,id';
        }

        return $rules;
    }
}
