<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('manage user profiles');
    }

    public function rules(): array
    {
        return [
            'name'     => ['required', 'string', 'min:2', 'max:255'],
            'email'    => ['required', 'email:rfc,dns', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', Password::min(8)
                                ->mixedCase()
                                ->numbers()
                                ->uncompromised(),
            ],
            'role'      => ['required', 'string', 'exists:roles,name'],
            'manager_id'=> ['nullable', 'exists:users,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min'          => 'Name must be at least 2 characters.',
            'email.email'       => 'Please enter a valid email address.',
            'email.unique'      => 'This email is already registered.',
            'email.dns'         => 'This email domain does not exist.',
            'password.min'      => 'Password must be at least 8 characters.',
            'role.exists'       => 'The selected role is invalid.',
            'manager_id.exists' => 'The selected manager does not exist.',
        ];
    }

    public function attributes(): array
    {
        return [
            'name'       => 'Full Name',
            'email'      => 'Email Address',
            'password'   => 'Password',
            'role'       => 'Role',
            'manager_id' => 'Manager',
        ];
    }
}
