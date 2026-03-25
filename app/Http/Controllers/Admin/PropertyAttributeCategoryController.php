<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyAttributeCategoryRequest;
use App\Models\PropertyAttributeCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyAttributeCategoryController extends Controller
{
    public function index(): View
    {
        $categories = PropertyAttributeCategory::latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Ingatlan attribútum kategóriák',
            'pageTitle' => 'Ingatlan attribútum kategóriák',
            'items' => $categories,
            'columns' => [
                ['name' => 'name', 'label' => 'Megnevezés'],
                ['name' => 'description', 'label' => 'Leírás'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'boolean'],
            ],
            'createRoute' => 'admin.property-attribute-categories.create',
            'editRoute' => 'admin.property-attribute-categories.edit',
            'destroyRoute' => 'admin.property-attribute-categories.destroy',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új attribútum kategória',
            'pageTitle' => 'Új attribútum kategória',
            'fields' => [
                ['name' => 'name', 'label' => 'Megnevezés', 'type' => 'text', 'required' => true],
                ['name' => 'description', 'label' => 'Leírás', 'type' => 'textarea'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'checkbox', 'checkbox_label' => 'Aktív'],
            ],
            'storeRoute' => 'admin.property-attribute-categories.store',
            'indexRoute' => 'admin.property-attribute-categories.index',
        ]);
    }

    public function store(PropertyAttributeCategoryRequest $request): RedirectResponse
    {
        PropertyAttributeCategory::create($request->validated());
        
        return redirect()->route('admin.property-attribute-categories.index')
            ->with('success', 'Attribútum kategória sikeresen létrehozva.');
    }

    public function show(PropertyAttributeCategory $propertyAttributeCategory): View
    {
        return view('admin.crud.show', [
            'title' => 'Attribútum kategória részletei',
            'pageTitle' => 'Attribútum kategória részletei',
            'item' => $propertyAttributeCategory,
            'fields' => [
                ['name' => 'name', 'label' => 'Megnevezés'],
                ['name' => 'description', 'label' => 'Leírás'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'boolean'],
            ],
            'indexRoute' => 'admin.property-attribute-categories.index',
            'editRoute' => 'admin.property-attribute-categories.edit',
        ]);
    }

    public function edit(PropertyAttributeCategory $propertyAttributeCategory): View
    {
        return view('admin.crud.edit', [
            'title' => 'Attribútum kategória szerkesztése',
            'pageTitle' => 'Attribútum kategória szerkesztése',
            'item' => $propertyAttributeCategory,
            'fields' => [
                ['name' => 'name', 'label' => 'Megnevezés', 'type' => 'text', 'required' => true],
                ['name' => 'description', 'label' => 'Leírás', 'type' => 'textarea'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'checkbox', 'checkbox_label' => 'Aktív'],
            ],
            'updateRoute' => 'admin.property-attribute-categories.update',
            'indexRoute' => 'admin.property-attribute-categories.index',
        ]);
    }

    public function update(PropertyAttributeCategoryRequest $request, PropertyAttributeCategory $propertyAttributeCategory): RedirectResponse
    {
        $propertyAttributeCategory->update($request->validated());
        
        return redirect()->route('admin.property-attribute-categories.index')
            ->with('success', 'Attribútum kategória sikeresen frissítve.');
    }

    public function destroy(PropertyAttributeCategory $propertyAttributeCategory): RedirectResponse
    {
        $propertyAttributeCategory->delete();
        
        return redirect()->route('admin.property-attribute-categories.index')
            ->with('success', 'Attribútum kategória sikeresen törölve.');
    }
}

