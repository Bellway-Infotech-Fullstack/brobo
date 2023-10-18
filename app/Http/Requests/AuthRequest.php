<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Route;


class AuthRequest extends FormRequest
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
        // Determine the current route name
        $routeName = Route::currentRouteName();

        // Define conditional validation rules based on the route name
        $rules = [];

        switch ($routeName) {
            case 'register':
                $rules = [
                    'name' => 'required|regex:/^[A-Za-z\s]+$/',
                    'mobile_number' => 'required|integer|regex:/^\d{10}$/|unique:users',
                    'password' => 'required|min:8',
                    'address' => 'required',
                ];
                break;

            case 'test':
                $rules = [
                    'mobile_number' => 'required|integer|regex:/^\d{10}$/',
                    'password' => 'required|alphanum|min:8',
                    'role_id' => 'required|integer',
                ];
                break;

            case 'updateProfile':
                $rules = [
                    'customer_id' => 'required',
                    'name' => 'required',
                    'email' => 'required',
                ];
                break;

            // Add more cases for other routes as needed

            default:
                // Handle validation rules for other routes or provide default rules
                break;
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'name.required' => 'The name field is required.',
            'password.required' => 'The password confirmation does not match.',
            // Add more custom messages as needed
        ];
    }
}

