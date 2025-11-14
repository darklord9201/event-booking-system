<?php

namespace App\Http\Requests\Ticket;

use App\Http\Requests\BaseFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends BaseFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => [
                'required',
                Rule::in('VIP', 'Platinum', 'Gold', 'Standard'),
            ],
            'price' => 'required|decimal:0,2',
            'quantity' => 'required|integer'
        ];
    }

    public function messages()
    {
       return [
           'type.in' => "Invalid ticket type. (Valid Types : 'VIP', 'Platinum', 'Gold', 'Standard')"
       ];
    }
}
