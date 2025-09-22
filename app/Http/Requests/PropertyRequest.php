<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PropertyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        // only allow updates if the user is logged in
        return backpack_auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'ad_type' => 'required|in:sell,rent,sell_and_rent',
            'property_code' => 'required|string|max:10',
            'settlement_id' => 'required|exists:settlements,id',
            'property_type_id' => 'required|exists:property_types,id',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max per image
            // Document fields should not interfere with property validation
            'name' => 'nullable',
            'category' => 'nullable',
            'file' => 'nullable',
            'description' => 'nullable',
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
            'title' => 'cím',
            'price' => 'ár',
            'ad_type' => 'hirdetés típusa',
            'property_code' => 'ingatlan kód',
            'settlement_id' => 'település',
            'property_type_id' => 'ingatlan típus',
            'images' => 'képek',
            'images.*' => 'kép',
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
            'title.required' => 'A cím mező kitöltése kötelező.',
            'title.string' => 'A cím csak szöveg lehet.',
            'title.max' => 'A cím legfeljebb 255 karakter lehet.',
            'price.required' => 'Az ár mező kitöltése kötelező.',
            'price.numeric' => 'Az ár csak szám lehet.',
            'price.min' => 'Az ár nem lehet negatív.',
            'ad_type.required' => 'A hirdetés típusa mező kitöltése kötelező.',
            'ad_type.in' => 'A hirdetés típusa csak eladó, kiadó vagy mindkettő lehet.',
            'property_code.required' => 'Az ingatlan kód mező kitöltése kötelező.',
            'property_code.string' => 'Az ingatlan kód csak szöveg lehet.',
            'property_code.max' => 'Az ingatlan kód legfeljebb 10 karakter lehet.',
            'settlement_id.required' => 'A település kiválasztása kötelező.',
            'settlement_id.exists' => 'A kiválasztott település nem létezik.',
            'property_type_id.required' => 'Az ingatlan típus kiválasztása kötelező.',
            'property_type_id.exists' => 'A kiválasztott ingatlan típus nem létezik.',
            'images.array' => 'A képek csak tömb formátumban adhatók meg.',
            'images.*.image' => 'A feltöltött fájl kép formátumú kell legyen.',
            'images.*.mimes' => 'A kép csak jpeg, png, jpg vagy gif formátumú lehet.',
            'images.*.max' => 'A kép mérete nem lehet nagyobb 10MB-nál.',
        ];
    }
}
