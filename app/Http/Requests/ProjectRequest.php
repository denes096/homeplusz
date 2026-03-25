<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'partner_id' => 'nullable|exists:partners,id',
            'storage_count' => 'required|integer|min:0',
            'storage_type' => 'required|in:fixed,optional',
            'is_required_storage' => 'required|boolean',
            'project_code' => 'required|integer',
            'images' => 'nullable|array',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240', // 10MB max per image
            'existing_images' => 'nullable|string', // JSON string of existing images
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
            'name' => 'név',
            'title' => 'összefoglaló',
            'description' => 'leírás',
            'user_id' => 'referens',
            'partner_id' => 'partner',
            'storage_count' => 'tárolók száma',
            'storage_type' => 'tároló típusa',
            'is_required_storage' => 'kötelező megvásárolni',
            'project_code' => 'projekt azonosító',
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
            'name.required' => 'A név megadása kötelező.',
            'title.required' => 'Az összefoglaló megadása kötelező.',
            'description.required' => 'A leírás megadása kötelező.',
            'storage_count.required' => 'A tárolók száma megadása kötelező.',
            'storage_count.integer' => 'A tárolók száma csak egész szám lehet.',
            'storage_count.min' => 'A tárolók száma nem lehet negatív.',
            'storage_type.required' => 'A tároló típusa megadása kötelező.',
            'storage_type.in' => 'A tároló típusa csak "Fixen hozzárendelt" vagy "Bármelyik választható" lehet.',
            'is_required_storage.required' => 'A "Kötelező megvásárolni" mező megadása kötelező.',
            'project_code.required' => 'A projekt azonosító megadása kötelező.',
        ];
    }
}
