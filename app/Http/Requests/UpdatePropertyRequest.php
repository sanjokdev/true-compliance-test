<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePropertyRequest extends FormRequest
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
            'organisation' => 'sometimes|required|max:255',
            'property_type' => 'sometimes|required|max:255',
            'parent_property_id' => 'nullable',
            'uprn' => 'sometimes|required|max:255',
            'address' => 'sometimes|required',
            'town' => 'sometimes|required|max:255',
            'postcode' => 'sometimes|required|max:20',
            'live' => 'sometimes|required|boolean',
        ];
    }
}
