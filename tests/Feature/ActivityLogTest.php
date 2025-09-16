<?php

namespace Tests\Feature;

use App\Models\Customers;
use App\Models\Property;
use App\Models\User;
use App\Services\ActivityLogService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Activitylog\Models\Activity;
use Tests\TestCase;

class ActivityLogTest extends TestCase
{
    use RefreshDatabase;

    public function test_property_creation_logs_activity(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::create([
            'title' => 'Test Property',
            'price' => 1000000,
            'property_code' => 'TEST001',
            'is_active' => true,
            'featured' => false,
            'ad_type' => 'sell',
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Property::class,
            'subject_id' => $property->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'created',
        ]);

        $activity = Activity::where('subject_id', $property->id)->first();
        $this->assertStringContainsString('Ingatlan hozzáadva: TEST001', $activity->description);
    }

    public function test_property_update_logs_activity(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::create([
            'title' => 'Test Property',
            'price' => 1000000,
            'property_code' => 'TEST001',
            'is_active' => true,
            'featured' => false,
            'ad_type' => 'sell',
            'user_id' => $user->id,
        ]);

        $property->update([
            'title' => 'Updated Property',
            'price' => 1500000,
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Property::class,
            'subject_id' => $property->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'updated',
        ]);

        $activity = Activity::where('subject_id', $property->id)
            ->where('event', 'updated')
            ->first();
        $this->assertStringContainsString('Ingatlan módosítva: TEST001', $activity->description);
    }

    public function test_customer_creation_logs_activity(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $customer = Customers::create([
            'name_0' => 'Test Customer',
            'phone_0' => '123456789',
            'email' => 'test@example.com',
            'ekod' => 'CUST001',
            'status' => 'Aktív',
        ]);

        $this->assertDatabaseHas('activity_log', [
            'subject_type' => Customers::class,
            'subject_id' => $customer->id,
            'causer_type' => User::class,
            'causer_id' => $user->id,
            'event' => 'created',
        ]);

        $activity = Activity::where('subject_id', $customer->id)->first();
        $this->assertStringContainsString('Új vevő rögzítve: Test Customer', $activity->description);
    }

    public function test_activity_log_service_returns_recent_activities(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create some activities
        Property::create([
            'title' => 'Test Property 1',
            'price' => 1000000,
            'property_code' => 'TEST001',
            'is_active' => true,
            'featured' => false,
            'ad_type' => 'sell',
            'user_id' => $user->id,
        ]);

        Property::create([
            'title' => 'Test Property 2',
            'price' => 2000000,
            'property_code' => 'TEST002',
            'is_active' => true,
            'featured' => false,
            'ad_type' => 'rent',
            'user_id' => $user->id,
        ]);

        $service = new ActivityLogService;
        $activities = $service->getRecentActivities(5);

        $this->assertCount(2, $activities);
        $this->assertInstanceOf(Activity::class, $activities->first());
    }

    public function test_activity_log_service_formats_descriptions_correctly(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $property = Property::create([
            'title' => 'Test Property',
            'price' => 1000000,
            'property_code' => 'TEST001',
            'is_active' => true,
            'featured' => false,
            'ad_type' => 'sell',
            'user_id' => $user->id,
        ]);

        $activity = Activity::where('subject_id', $property->id)->first();
        $service = new ActivityLogService;

        $formattedDescription = $service->formatActivityDescription($activity);

        $this->assertStringContainsString($user->name, $formattedDescription);
        $this->assertStringContainsString('TEST001', $formattedDescription);
    }
}
