<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertySubtypeRequest;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PropertySubtypeController extends Controller
{
    public function index(Request $request): View
    {
        $query = PropertySubtype::with('propertyType');
        
        if ($request->has('property-type-id')) {
            $query->where('property_type_id', $request->get('property-type-id'));
        }
        
        $propertySubtypes = $query->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Ingatlan altípusok',
            'pageTitle' => 'Ingatlan altípusok',
            'items' => $propertySubtypes,
            'columns' => [
                [
                    'name' => 'propertyType',
                    'label' => 'Főtípus',
                    'type' => 'relationship',
                    'relationship' => 'propertyType',
                    'attribute' => 'name',
                ],
                ['name' => 'name', 'label' => 'Megnevezés'],
            ],
            'createRoute' => 'admin.property-subtypes.create',
            'editRoute' => 'admin.property-subtypes.edit',
            'destroyRoute' => 'admin.property-subtypes.destroy',
        ]);
    }

    public function create(): View
    {
        $propertyTypes = PropertyType::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.crud.create', [
            'title' => 'Új ingatlan altípus',
            'pageTitle' => 'Új ingatlan altípus',
            'fields' => [
                [
                    'name' => 'property_type_id',
                    'label' => 'Főtípus',
                    'type' => 'select',
                    'options' => $propertyTypes,
                    'required' => true,
                ],
                ['name' => 'name', 'label' => 'Megnevezés', 'type' => 'text', 'required' => true],
            ],
            'storeRoute' => 'admin.property-subtypes.store',
            'indexRoute' => 'admin.property-subtypes.index',
        ]);
    }

    public function store(PropertySubtypeRequest $request): RedirectResponse
    {
        PropertySubtype::create($request->validated());
        
        return redirect()->route('admin.property-subtypes.index')
            ->with('success', 'Ingatlan altípus sikeresen létrehozva.');
    }

    public function show(PropertySubtype $propertySubtype): View
    {
        $propertySubtype->load('propertyType');
        
        return view('admin.crud.show', [
            'title' => 'Ingatlan altípus részletei',
            'pageTitle' => 'Ingatlan altípus részletei',
            'item' => $propertySubtype,
            'fields' => [
                [
                    'name' => 'propertyType',
                    'label' => 'Főtípus',
                    'type' => 'relationship',
                    'relationship' => 'propertyType',
                    'attribute' => 'name',
                ],
                ['name' => 'name', 'label' => 'Megnevezés'],
            ],
            'indexRoute' => 'admin.property-subtypes.index',
            'editRoute' => 'admin.property-subtypes.edit',
        ]);
    }

    public function edit(PropertySubtype $propertySubtype): View
    {
        $propertyTypes = PropertyType::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.crud.edit', [
            'title' => 'Ingatlan altípus szerkesztése',
            'pageTitle' => 'Ingatlan altípus szerkesztése',
            'item' => $propertySubtype,
            'fields' => [
                [
                    'name' => 'property_type_id',
                    'label' => 'Főtípus',
                    'type' => 'select',
                    'options' => $propertyTypes,
                    'required' => true,
                ],
                ['name' => 'name', 'label' => 'Megnevezés', 'type' => 'text', 'required' => true],
            ],
            'updateRoute' => 'admin.property-subtypes.update',
            'indexRoute' => 'admin.property-subtypes.index',
        ]);
    }

    public function update(PropertySubtypeRequest $request, PropertySubtype $propertySubtype): RedirectResponse
    {
        $propertySubtype->update($request->validated());
        
        return redirect()->route('admin.property-subtypes.index')
            ->with('success', 'Ingatlan altípus sikeresen frissítve.');
    }

    public function destroy(PropertySubtype $propertySubtype): RedirectResponse
    {
        $propertySubtype->delete();
        
        return redirect()->route('admin.property-subtypes.index')
            ->with('success', 'Ingatlan altípus sikeresen törölve.');
    }
}

