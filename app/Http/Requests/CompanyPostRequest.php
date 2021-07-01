<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CompanyPostRequest extends FormRequest
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
            'name' => 'required',
            'email' => ['email', Rule::unique('companies', 'email')->ignore($this->company), 'max:255'],
            'website' => 'string',
            'logo' => 'mimes:jpeg,png,jpg|dimensions:min_width=100,min_height=100'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $result['success'] = false;
        $result['message'] = $validator->errors()->first();

        throw new HttpResponseException(response()->json($result, 200));
    }
}
