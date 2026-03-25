<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomersRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'status' => 'required|in:Aktív,Felfüggesztve,Archív',
            'refId' => 'nullable|exists:users,id',
            'kategoria' => 'required|in:maganszemely,beruhazo',
            'name_0' => 'required|string|max:100',
            'phone_0' => 'nullable|string|max:100',
            'azonosito1_0' => 'nullable|string|max:100',
            'azonosito2_0' => 'nullable|string|max:100',
            'name_1' => 'nullable|string|max:100',
            'phone_1' => 'nullable|string|max:100',
            'name_2' => 'nullable|string|max:100',
            'phone_2' => 'nullable|string|max:100',
            'name_3' => 'nullable|string|max:100',
            'phone_3' => 'nullable|string|max:100',
            'name_4' => 'nullable|string|max:100',
            'phone_4' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'address' => 'nullable|string|max:255',
            'note' => 'nullable|string|max:250',
        ];
    }

    /**
     * Get the validation attributes that apply to the request.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'status' => 'státusz',
            'refId' => 'referens',
            'kategoria' => 'kategória',
            'name_0' => 'név',
            'phone_0' => 'telefonszám',
            'email' => 'email cím',
            'address' => 'cím',
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'status.required' => 'A státusz megadása kötelező.',
            'status.in' => 'A státusz csak Aktív, Felfüggesztve vagy Archív lehet.',
            'kategoria.required' => 'A kategória megadása kötelező.',
            'kategoria.in' => 'A kategória csak magánszemély vagy beruházó lehet.',
            'name_0.required' => 'A név megadása kötelező.',
            'email.email' => 'Az email cím formátuma nem megfelelő.',
        ];
    }
}
