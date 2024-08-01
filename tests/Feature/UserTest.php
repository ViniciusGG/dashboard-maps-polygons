<?php

use App\Models\User;

it('can create a user', function () {
    $user = createUser();

    expect($user)->toBeInstanceOf(User::class);
});

it('can update a user', function () {
    $user = createUser();

    $novoNome = 'Novo Nome';
    $user->first_name = $novoNome;
    $user->save();

    expect($user->first_name)->toBe($novoNome);
});

it('can delete a user', function () {
    $user = createUser();

    $user->delete();

    expect(User::find($user->id))->toBeNull();
});