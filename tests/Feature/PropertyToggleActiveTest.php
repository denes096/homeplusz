<?php

use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows activation button for inactive property', function () {
    $user = User::factory()->admin()->create();
    $property = Property::factory()->create(['is_active' => false]);

    $this->actingAs($user)
        ->get('/admin/property')
        ->assertSee('Aktiválás')
        ->assertDontSee('Deaktiválás');
});

it('shows deactivation button for active property', function () {
    $user = User::factory()->admin()->create();
    $property = Property::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->get('/admin/property')
        ->assertSee('Deaktiválás')
        ->assertDontSee('Aktiválás');
});

it('toggles property from inactive to active', function () {
    $user = User::factory()->admin()->create();
    $property = Property::factory()->create(['is_active' => false]);

    $response = $this->actingAs($user)
        ->post("/admin/property/{$property->id}/toggle-active");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'is_active' => true,
            'message' => 'Ingatlan aktiválva',
        ]);

    $property->refresh();
    expect($property->is_active)->toBeTrue();
});

it('toggles property from active to inactive', function () {
    $user = User::factory()->admin()->create();
    $property = Property::factory()->create(['is_active' => true]);

    $response = $this->actingAs($user)
        ->post("/admin/property/{$property->id}/toggle-active");

    $response->assertOk()
        ->assertJson([
            'success' => true,
            'is_active' => false,
            'message' => 'Ingatlan deaktiválva',
        ]);

    $property->refresh();
    expect($property->is_active)->toBeFalse();
});

it('requires admin access to toggle property', function () {
    $user = User::factory()->create(); // Regular user without admin role
    $property = Property::factory()->create(['is_active' => true]);

    $this->actingAs($user)
        ->post("/admin/property/{$property->id}/toggle-active")
        ->assertForbidden();
});

it('returns 404 for non-existent property', function () {
    $user = User::factory()->admin()->create();

    $this->actingAs($user)
        ->post('/admin/property/999/toggle-active')
        ->assertNotFound();
});
