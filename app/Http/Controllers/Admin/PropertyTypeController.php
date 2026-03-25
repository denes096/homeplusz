<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyTypeRequest;
use App\Models\PropertyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyTypeController extends Controller
{
    public function index(): View
    {
        $propertyTypes = PropertyType::withCount('subtypes')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Ingatlan típusok',
            'pageTitle' => 'Ingatlan típusok',
            'items' => $propertyTypes,
            'columns' => [
                ['name' => 'name', 'label' => 'Ingatlan típus'],
                [
                    'name' => 'subtypes_count',
                    'label' => 'Altípusok',
                    'type' => 'custom',
                    'value' => function($item) {
                        return '<a href="' . route('admin.property-subtypes.index', ['property-type-id' => $item->id]) . '">' . $item->subtypes_count . ' altípus</a>';
                    }
                ],
            ],
            'createRoute' => 'admin.property-types.create',
            'editRoute' => 'admin.property-types.edit',
            'destroyRoute' => 'admin.property-types.destroy',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új ingatlan típus',
            'pageTitle' => 'Új ingatlan típus',
            'fields' => [
                ['name' => 'name', 'label' => 'Ingatlan típus', 'type' => 'text', 'required' => true],
            ],
            'storeRoute' => 'admin.property-types.store',
            'indexRoute' => 'admin.property-types.index',
        ]);
    }

    public function store(PropertyTypeRequest $request): RedirectResponse
    {
        PropertyType::create($request->validated());
        
        return redirect()->route('admin.property-types.index')
            ->with('success', 'Ingatlan típus sikeresen létrehozva.');
    }

    public function show(PropertyType $propertyType): View
    {
        $propertyType->loadCount('subtypes');
        
        return view('admin.crud.show', [
            'title' => 'Ingatlan típus részletei',
            'pageTitle' => 'Ingatlan típus részletei',
            'item' => $propertyType,
            'fields' => [
                ['name' => 'name', 'label' => 'Ingatlan típus'],
                ['name' => 'subtypes_count', 'label' => 'Altípusok száma'],
            ],
            'indexRoute' => 'admin.property-types.index',
            'editRoute' => 'admin.property-types.edit',
        ]);
    }

    public function edit(PropertyType $propertyType): View
    {
        return view('admin.crud.edit', [
            'title' => 'Ingatlan típus szerkesztése',
            'pageTitle' => 'Ingatlan típus szerkesztése',
            'item' => $propertyType,
            'fields' => [
                ['name' => 'name', 'label' => 'Ingatlan típus', 'type' => 'text', 'required' => true],
            ],
            'updateRoute' => 'admin.property-types.update',
            'indexRoute' => 'admin.property-types.index',
        ]);
    }

    public function update(PropertyTypeRequest $request, PropertyType $propertyType): RedirectResponse
    {
        $propertyType->update($request->validated());
        
        return redirect()->route('admin.property-types.index')
            ->with('success', 'Ingatlan típus sikeresen frissítve.');
    }

    public function destroy(PropertyType $propertyType): RedirectResponse
    {
        $propertyType->delete();
        
        return redirect()->route('admin.property-types.index')
            ->with('success', 'Ingatlan típus sikeresen törölve.');
    }
}

