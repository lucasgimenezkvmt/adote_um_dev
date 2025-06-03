<?php

it('has splashscreen page', function () {
    $response = $this->get('/primeiraRota');

    $response->assertStatus(200);
});
