<?php

use App\Models\SliderImages;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

it('can list slider images with active status and image preview', function () {
    Storage::fake('public');

    $sliderImage = SliderImages::factory()->create([
        'name' => 'Test Slider Image',
        'path' => 'uploads/test-image.jpg',
        'active' => true,
    ]);

    $response = $this->get('/admin/slider-images');

    $response->assertStatus(200)
        ->assertSee('Aktív')
        ->assertSee($sliderImage->name)
        ->assertSee('uploads/test-image.jpg');
});

it('can create a new slider image with active status', function () {
    Storage::fake('public');

    $image = UploadedFile::fake()->image('slider.jpg');

    $data = [
        'name' => 'New Slider Image',
        'path' => $image,
        'active' => true,
    ];

    $response = $this->post('/admin/slider-images', $data);

    $this->assertDatabaseHas('slider_images', [
        'name' => 'New Slider Image',
        'active' => 1,
    ]);
});

it('can update slider image active status', function () {
    Storage::fake('public');

    $sliderImage = SliderImages::factory()->create([
        'name' => 'Test Image',
        'path' => 'uploads/test.jpg',
        'active' => true,
    ]);

    $data = [
        'name' => 'Updated Image',
        'path' => $sliderImage->path,
        'active' => false,
    ];

    $response = $this->put("/admin/slider-images/{$sliderImage->id}", $data);

    $this->assertDatabaseHas('slider_images', [
        'id' => $sliderImage->id,
        'name' => 'Updated Image',
        'active' => 0,
    ]);
});

it('shows current image preview on update form', function () {
    Storage::fake('public');

    $sliderImage = SliderImages::factory()->create([
        'name' => 'Test Image',
        'path' => 'uploads/test.jpg',
        'active' => true,
    ]);

    $response = $this->get("/admin/slider-images/{$sliderImage->id}/edit");

    $response->assertStatus(200)
        ->assertSee('Jelenlegi kép:')
        ->assertSee('uploads/test.jpg');
});

it('can filter active slider images', function () {
    Storage::fake('public');

    SliderImages::factory()->create(['active' => true]);
    SliderImages::factory()->create(['active' => false]);

    $response = $this->get('/admin/slider-images?active=1');

    $response->assertStatus(200);
});

it('can filter inactive slider images', function () {
    Storage::fake('public');

    SliderImages::factory()->create(['active' => true]);
    SliderImages::factory()->create(['active' => false]);

    $response = $this->get('/admin/slider-images?active=0');

    $response->assertStatus(200);
});
