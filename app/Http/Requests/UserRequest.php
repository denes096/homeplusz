<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        // Get the user from the route parameter - try different parameter names
        $user = $this->route('user');
        $userId = null;

        if ($user) {
            // If it's already a model instance
            if (is_object($user) && method_exists($user, 'getKey')) {
                $userId = $user->getKey();
            } else {
                // If it's just an ID
                $userId = $user;
            }
        } else {
            // Fallback: try to get user ID from request segments (for edit forms)
            $segments = $this->segments();
            $userIndex = array_search('user', $segments);
            if ($userIndex !== false && isset($segments[$userIndex + 1])) {
                $userId = $segments[$userIndex + 1];
            }
        }

        $emailRule = 'required|email|max:255';
        if ($userId) {
            $emailRule .= '|unique:users,email,' . $userId . ',id';
        } else {
            $emailRule .= '|unique:users,email';
        }

        // Debug: Log the validation rule being used (temporarily disabled)
        // \Log::info('UserRequest validation rule for email: ' . $emailRule . ' (userId: ' . $userId . ')');

        return [
            'name' => 'required|string|max:255',
            'email' => $emailRule,
            'password' => 'nullable|string|min:8|confirmed',
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
            //
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
            //
        ];
    }
}
