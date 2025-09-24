<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\Partners;
use App\Models\Project;
use App\Models\Property;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Show the admin dashboard with user statistics
     */
    public function index(): View
    {
        // Get all users with their related counts
        $users = User::with(['properties', 'projects', 'customers', 'partners'])
            ->where('id', '!=', 1) // Exclude admin user if needed
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'properties_count' => $user->properties()->count(),
                    'projects_count' => $user->projects()->count(),
                    'customers_count' => $user->customers()->count(),
                    'partners_count' => $user->partners()->count(),
                    'total_count' => $user->properties()->count() +
                                  $user->projects()->count() +
                                  $user->customers()->count() +
                                  $user->partners()->count(),
                    'created_at' => $user->created_at,
                ];
            })
            ->sortByDesc('total_count')
            ->values();

        // Get overall statistics
        $totalUsers = User::count();
        $totalProperties = Property::count();
        $totalProjects = Project::count();
        $totalCustomers = Customers::count();
        $totalPartners = Partners::count();

        return view('vendor.backpack.dashboard.index', [
            'users' => $users,
            'stats' => [
                'total_users' => $totalUsers,
                'total_properties' => $totalProperties,
                'total_projects' => $totalProjects,
                'total_customers' => $totalCustomers,
                'total_partners' => $totalPartners,
            ],
        ]);
    }
}
