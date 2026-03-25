<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSearchRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
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
            'customer_id' => 'required|exists:customers,id',
            'search' => 'required|json',
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
            'customer_id' => 'vevő',
            'search' => 'keresési paraméterek',
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
            'customer_id.required' => 'A vevő kiválasztása kötelező.',
            'customer_id.exists' => 'A kiválasztott vevő nem létezik.',
            'search.required' => 'A keresési paraméterek megadása kötelező.',
            'search.json' => 'A keresési paramétereknek érvényes JSON formátumban kell lenniük.',
        ];
    }
}
