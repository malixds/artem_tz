<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest

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
        $uuid = $this->route('uuid');
        return [
            'name' => 'sometimes|required|string|max:255', // Имя пользователя (необязательно, но если указано, то обязательно)
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $uuid, // Уникальный email, исключая текущего пользователя
            'password' => 'sometimes|required|string|min:8', // Пароль (необязательно, но если указан, то минимум 8 символов, подтверждение пароля)
        ];
    }
}
