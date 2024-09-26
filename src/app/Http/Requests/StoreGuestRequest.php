<?php

namespace App\Http\Requests;

use App\Enums\CountryCode;
use App\Rules\FormText;
use App\Rules\Phone;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreGuestRequest extends FormRequest
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
            'first_name' => ['required', new FormText],
            'second_name' => ['required', new FormText],
            'email' => [
                'nullable',
                'email',
                'unique:guests,email'
            ],
            'phone' => [
                'required',
                'max:16',
                'unique:guests,phone',
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

    protected function prepareForValidation(): void
    {
        if (! $this->input('country') && $this->input('phone')) {
            $country = CountryCode::fromPhoneNumber($this->input('phone'));

            if ($country) {
                $this->merge(['country' => $country->name]);
            }
        }
    }
}
