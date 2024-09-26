<?php

namespace App\Http\Requests;

use App\Rules\FormText;
use App\Rules\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UpdateGuestRequest extends FormRequest
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
            'first_name' => ['nullable', new FormText],
            'second_name' => ['nullable', new FormText],
            'email' => [
                'nullable',
                'email',
                Rule::unique('guests', 'email')->ignore($this->guest),
            ],
            'phone' => [
                'nullable',
                Rule::unique('guests', 'phone')->ignore($this->guest),
                new Phone,
            ],
            'country' => ['nullable', new FormText],
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => 'Incorrect data',
            'data'      => $validator->errors()
        ], 422));
    }
}
