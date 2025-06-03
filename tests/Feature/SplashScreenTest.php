<?php
use function Pest\Livewire\livewire; 

it('has splashscreen page', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
});

it('component was rendered', function() {
    $response = $this->get('/');

    $response->assertSeeLivewire('components.splash-screen');
});