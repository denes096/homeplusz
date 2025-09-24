<?php

namespace Tests\Unit\Admin;

use App\Models\Customers;
use App\Models\Partners;
use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test dashboard index method returns correct view with data
     */
    public function test_index_returns_dashboard_view_with_user_statistics()
    {
        // Create test data
        $user = User::factory()->create();
        $property = Property::factory()->create(['user_id' => $user->id]);
        $project = Project::factory()->create(['user_id' => $user->id]);
        $customer = Customers::factory()->create(['refId' => $user->id]);
        $partner = Partners::factory()->create(['user_id' => $user->id]);

        // Call the controller method
        $response = $this->get('/admin/dashboard');

        // Assert response is successful
        $response->assertStatus(200);

        // Assert correct view is returned
        $response->assertViewIs('vendor.backpack.dashboard.index');

        // Assert view has required data
        $response->assertViewHas('users');
        $response->assertViewHas('stats');

        // Check if user statistics are calculated correctly
        $response->assertSee($user->name);
        $response->assertSee('1'); // Should show 1 property, 1 project, 1 customer, 1 partner
        $response->assertSee('4'); // Total count should be 4
    }

    /**
     * Test that dashboard excludes admin user from statistics
     */
    public function test_index_excludes_admin_user()
    {
        $adminUser = User::factory()->create(['id' => 1]);
        $regularUser = User::factory()->create();

        $response = $this->get('/admin/dashboard');

        $response->assertStatus(200);
        $response->assertViewHas('users');

        // Admin user should not be in the users collection
        $users = $response->getViewData()['users'];
        $this->assertFalse($users->contains('id', 1));
    }
}
