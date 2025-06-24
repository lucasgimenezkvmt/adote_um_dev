<?php

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Http\Livewire\Components\InterestScreen;
use function Pest\Laravel\get;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\assertDatabaseHas;
use function Pest\Livewire\livewire;

it('check if url is working', function () {
    $user = User::firstWhere('email', 'lucas_d360@hotmail.com');
        
    Auth::login($user);

    actingAs($user->load('profile'))
        ->get('/interesses')
        ->assertStatus(200);
    #$response = $this->get('/interesses');

    #$response->assertStatus(200);
});

it('check if interests form was stored successful', function () {
    $payload = '[{"category_id":1,"id":14,"level":1,"name":"SQL"},{"category_id":2,"id":21,"level":1,"name":"Bootstrap"},{"category_id":3,"id":36,"level":1,"name":"Alemao"}]';

    $user = User::firstWhere('email', 'lucas_d360@hotmail.com');
        
    actingAs($user->load('profile'));

    livewire(InterestScreen::class)
        ->set('payload', json_decode($payload, true, 512, JSON_THROW_ON_ERROR))
        ->call('save');

    assertDatabaseHas('interests', [
        'user_id' => $user->id
    ]);

});
