<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmployeePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['email', Rule::unique('employees', 'email')->ignore($this->employee), 'max:255'],
            'company_id' => 'not_in:0',
            'phone' => 'integer'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'company_id.not_in' => 'Please select company.'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $result['success'] = false;
        $result['message'] = $validator->errors()->first();

        throw new HttpResponseException(response()->json($result, 200));
    }
}
