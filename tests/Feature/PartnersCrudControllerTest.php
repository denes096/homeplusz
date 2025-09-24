<?php

use App\Models\Partners;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a partner', function () {
    $partner = Partners::factory()->create();

    expect($partner)->toBeInstanceOf(Partners::class);
    expect($partner->name)->toBeString();
});

it('can access partners index page', function () {
    $response = $this->get('/admin/partners');

    $response->assertStatus(200);
});
