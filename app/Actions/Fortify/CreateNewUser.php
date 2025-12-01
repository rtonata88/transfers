<?php

namespace App\Actions\Fortify;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    public function create(array $input): User
    {
        Validator::make($input, [
            'username' => [
                'required',
                'string',
                'min:3',
                'max:50',
                'alpha_num',
                Rule::unique(User::class),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ], [
            'username.alpha_num' => 'Username may only contain letters and numbers.',
            'username.unique' => 'This username is already taken.',
            'email.unique' => 'This email is already registered.',
        ])->validate();

        $user = User::create([
            'username' => strtolower($input['username']),
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
        ]);

        ActivityLog::log('register', 'New user registered', $user->id);

        return $user;
    }
}
