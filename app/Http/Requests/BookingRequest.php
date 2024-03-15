<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
            'address' => 'nullable|string',
            'city' => 'nullable|string',
            'zip' => 'nullable|string|numeric',
            'status' => 'required|string|in:pending,completed,cancelled',
            'payment_method' => 'required|string|in:midtrans,other_method',
            'payment_status' => 'required|string|in:pending,completed,cancelled',
            'payment_url' => 'nullable|string',
            'total_price' => 'nullable|integer',
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
        ];
    }
}
