<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::with('roles', 'permissions')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Felhasználók',
            'pageTitle' => 'Felhasználók',
            'items' => $users,
            'columns' => [
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'email', 'label' => 'Email'],
                [
                    'name' => 'roles',
                    'label' => 'Szerepkörök',
                    'type' => 'relationship',
                    'relationship' => 'roles',
                    'attribute' => 'name',
                ],
                [
                    'name' => 'permissions',
                    'label' => 'Jogosultságok',
                    'type' => 'relationship',
                    'relationship' => 'permissions',
                    'attribute' => 'name',
                ],
            ],
            'createRoute' => 'admin.users.create',
            'editRoute' => 'admin.users.edit',
            'destroyRoute' => 'admin.users.destroy',
            'showRoute' => 'admin.users.show',
        ]);
    }

    public function create(): View
    {
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        
        // Group permissions by their prefix (e.g., "view", "create", "update", "delete")
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.users.create', [
            'title' => 'Új felhasználó',
            'pageTitle' => 'Új felhasználó',
            'roles' => $roles,
            'permissions' => $permissions,
            'groupedPermissions' => $groupedPermissions,
            'storeRoute' => 'admin.users.store',
            'indexRoute' => 'admin.users.index',
        ]);
    }

    public function store(UserRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Handle password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $validated['profile_picture'] = $path;
        }
        
        // Remove password_confirmation and roles/permissions from validated data
        unset($validated['password_confirmation']);
        $roleIds = $request->input('roles', []);
        $permissionIds = $request->input('permissions', []);
        
        // Create user
        $user = User::create($validated);
        
        // Assign roles - convert IDs to role names
        if (!empty($roleIds)) {
            $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        } else {
            $user->syncRoles([]);
        }
        
        // Assign permissions - convert IDs to permission names
        if (!empty($permissionIds)) {
            $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            $user->syncPermissions($permissionNames);
        } else {
            $user->syncPermissions([]);
        }
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Felhasználó sikeresen létrehozva.');
    }

    public function show(User $user): View
    {
        $user->load(['roles', 'permissions']);
        
        return view('admin.users.show', [
            'title' => 'Felhasználó részletei',
            'pageTitle' => 'Felhasználó: ' . $user->name,
            'user' => $user,
            'indexRoute' => 'admin.users.index',
            'editRoute' => 'admin.users.edit',
        ]);
    }

    public function edit(User $user): View
    {
        $user->load(['roles', 'permissions']);
        $roles = Role::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        
        // Group permissions by their prefix
        $groupedPermissions = $permissions->groupBy(function ($permission) {
            $parts = explode(' ', $permission->name);
            return $parts[0] ?? 'other';
        });
        
        return view('admin.users.edit', [
            'title' => 'Felhasználó szerkesztése',
            'pageTitle' => 'Felhasználó szerkesztése',
            'user' => $user,
            'roles' => $roles,
            'permissions' => $permissions,
            'groupedPermissions' => $groupedPermissions,
            'updateRoute' => 'admin.users.update',
            'indexRoute' => 'admin.users.index',
        ]);
    }

    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        
        // Handle password
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        
        // Handle profile picture upload
        if ($request->hasFile('profile_picture')) {
            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
                Storage::disk('public')->delete($user->profile_picture);
            }
            
            $file = $request->file('profile_picture');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('uploads', $filename, 'public');
            $validated['profile_picture'] = $path;
        }
        
        // Remove password_confirmation and roles/permissions from validated data
        unset($validated['password_confirmation']);
        $roleIds = $request->input('roles', []);
        $permissionIds = $request->input('permissions', []);
        
        // Update user
        $user->update($validated);
        
        // Sync roles - convert IDs to role names
        if (!empty($roleIds)) {
            $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();
            $user->syncRoles($roleNames);
        } else {
            $user->syncRoles([]);
        }
        
        // Sync permissions - convert IDs to permission names
        if (!empty($permissionIds)) {
            $permissionNames = Permission::whereIn('id', $permissionIds)->pluck('name')->toArray();
            $user->syncPermissions($permissionNames);
        } else {
            $user->syncPermissions([]);
        }
        
        return redirect()->route('admin.users.show', $user->id)
            ->with('success', 'Felhasználó sikeresen frissítve.');
    }

    public function destroy(User $user): RedirectResponse
    {
        // Don't allow deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'Nem törölheted saját magadat.');
        }
        
        // Delete profile picture if exists
        if ($user->profile_picture && Storage::disk('public')->exists($user->profile_picture)) {
            Storage::disk('public')->delete($user->profile_picture);
        }
        
        $user->delete();
        
        return redirect()->route('admin.users.index')
            ->with('success', 'Felhasználó sikeresen törölve.');
    }
}

