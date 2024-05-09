<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Laravel\Jetstream\Jetstream;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array<string, string>  $input
     */
    protected function createRole(User $user)
    {
        if (request('role') === 'vendor') {
            $user->assignRole('vendor');
        }

        if (request('role') === 'customer') {
            $user->assignRole('customer');
        }
    }

    public function create(array $input): User
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
            // 'address' => ['required', 'string'],
            // 'latitude' => ['required', 'numeric'],
            // 'longitude' => ['required', 'numeric'],
        ])->validate();

        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'password' => Hash::make($input['password']),
                // 'address' => $input['address'],
                // 'latitude' => $input['latitude'],
                // 'longitude' => $input['longitude'],
            ]), function (User $user) {
                $this->createRole($user);
            });
        });
    }
}
