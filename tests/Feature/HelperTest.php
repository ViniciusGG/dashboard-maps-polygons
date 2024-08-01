<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use function Pest\Laravel\post;

function createUser($overrides = []) {
    return User::withoutEvents(function () use ($overrides) {
        return User::factory()->create(array_merge([
            'email' => 'teste@buzzvel.com',
            'password' => Hash::make('password'),
            'uuid' => Str::uuid(),
        ], $overrides));
    });
}

// function createMember($groups, $role, $user) {
//     foreach ($groups as $group) {
//         MemberGroup::factory()->create([
//             'user_id' => $user->id,
//             'group_id' => $group->id,
//             'role_id' => $role->id,
//         ]);
//     }
// }

function login() {

    $user = createUser(['two_factor_confirmed_at' => now()]);
    $response = post('/api/auth/login', [
        'email_or_phone' => $user->email,
        'password' => 'password',
    ]);
    return ['token' => $response->json('auth.token'), 'user' => $user];
}
