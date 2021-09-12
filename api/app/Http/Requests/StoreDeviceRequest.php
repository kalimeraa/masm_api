<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class StoreDeviceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'uid' => 'string|required',
            'app_id' => 'string|required',
            'language' => 'string|required',
            'os' => 'string|required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $response = response()->json([
            'status' => false,
            'message' => $validator->errors()
        ]);

        throw (new ValidationException($validator, $response))
            ->errorBag($this->errorBag)
            ->redirectTo($this->getRedirectUrl());
    }

    public function messages(): array
    {
        return [
            'uid.required' => trans('validation.required'),
            'uid.string' => trans('validation.string'),
            'app_id.required' => trans('validation.required'),
            'app_id.string' => trans('validation.string'),
            'language.required' => trans('validation.required'),
            'language.string' => trans('validation.string'),
            'os.required' => trans('validation.required'),
            'os.string' => trans('validation.string'),
        ];
    }
}
