<?php

use App\Models\User;

test('authenticated users can view the interactive api documentation', function () {
    $this->actingAs(User::factory()->create())
        ->get(route('api.docs'))
        ->assertOk()
        ->assertSee('Dokumentasi API')
        ->assertSee(route('api.openapi'), false)
        ->assertSee('swagger-ui');
});

test('the openapi contract is available as json', function () {
    $this->get(route('api.openapi'))
        ->assertOk()
        ->assertHeader('content-type', 'application/json')
        ->assertJsonPath('openapi', '3.0.3');
});
