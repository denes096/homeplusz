<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyAttributeRequest;
use App\Models\PropertyAttribute;
use App\Models\PropertyAttributeCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PropertyAttributeController extends Controller
{
    public function index(): View
    {
        $attributes = PropertyAttribute::with('category')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Ingatlan attribútumok',
            'pageTitle' => 'Ingatlan attribútumok',
            'items' => $attributes,
            'columns' => [
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'label', 'label' => 'Leírás'],
                ['name' => 'short_label', 'label' => 'Rövid leírás'],
                [
                    'name' => 'category',
                    'label' => 'Kategória',
                    'type' => 'relationship',
                    'relationship' => 'category',
                    'attribute' => 'name',
                ],
                [
                    'name' => 'type',
                    'label' => 'Típus',
                    'type' => 'custom',
                    'value' => function($item) {
                        $types = [
                            'text' => 'Szövegdoboz',
                            'checkbox' => 'Jelölőnégyzet',
                            'select' => 'Legördülő lista',
                            'radio' => 'Választógomb',
                            'number' => 'Szám',
                        ];
                        return $types[$item->type] ?? $item->type;
                    }
                ],
                ['name' => 'values', 'label' => 'Értékek'],
                ['name' => 'required', 'label' => 'Kötelező', 'type' => 'boolean'],
                ['name' => 'show_in_search', 'label' => 'Keresésben', 'type' => 'boolean'],
                ['name' => 'show_in_list', 'label' => 'Listázásban', 'type' => 'boolean'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'boolean'],
            ],
            'createRoute' => 'admin.property-attributes.create',
            'editRoute' => 'admin.property-attributes.edit',
            'destroyRoute' => 'admin.property-attributes.destroy',
            'showRoute' => 'admin.property-attributes.show',
        ]);
    }

    public function create(): View
    {
        $categories = PropertyAttributeCategory::where('active', 1)
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
        
        return view('admin.crud.create', [
            'title' => 'Új ingatlan attribútum',
            'pageTitle' => 'Új ingatlan attribútum',
            'fields' => $this->getFields($categories),
            'storeRoute' => 'admin.property-attributes.store',
            'indexRoute' => 'admin.property-attributes.index',
        ]);
    }

    public function store(PropertyAttributeRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['required'] = $request->has('required') ? 1 : 0;
        $data['show_in_search'] = $request->has('show_in_search') ? 1 : 0;
        $data['show_in_list'] = $request->has('show_in_list') ? 1 : 0;
        $data['active'] = $request->has('active') ? 1 : 0;
        
        PropertyAttribute::create($data);
        
        return redirect()->route('admin.property-attributes.index')
            ->with('success', 'Ingatlan attribútum sikeresen létrehozva.');
    }

    public function show(PropertyAttribute $propertyAttribute): View
    {
        $propertyAttribute->load('category');
        
        return view('admin.crud.show', [
            'title' => 'Ingatlan attribútum részletei',
            'pageTitle' => 'Ingatlan attribútum részletei',
            'item' => $propertyAttribute,
            'fields' => [
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'label', 'label' => 'Leírás'],
                ['name' => 'short_label', 'label' => 'Rövid leírás'],
                [
                    'name' => 'category',
                    'label' => 'Kategória',
                    'type' => 'relationship',
                    'relationship' => 'category',
                    'attribute' => 'name',
                ],
                [
                    'name' => 'type',
                    'label' => 'Típus',
                    'type' => 'custom',
                    'value' => function($item) {
                        $types = [
                            'text' => 'Szövegdoboz',
                            'checkbox' => 'Jelölőnégyzet',
                            'select' => 'Legördülő lista',
                            'radio' => 'Választógomb',
                            'number' => 'Szám',
                        ];
                        return $types[$item->type] ?? $item->type;
                    }
                ],
                ['name' => 'values', 'label' => 'Értékek'],
                ['name' => 'suffix', 'label' => 'Utótag'],
                ['name' => 'prefix', 'label' => 'Előtag'],
                ['name' => 'required', 'label' => 'Kötelező', 'type' => 'boolean'],
                ['name' => 'show_in_search', 'label' => 'Keresésben megjelenik', 'type' => 'boolean'],
                ['name' => 'show_in_list', 'label' => 'Listázásban megjelenik', 'type' => 'boolean'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'boolean'],
            ],
            'indexRoute' => 'admin.property-attributes.index',
            'editRoute' => 'admin.property-attributes.edit',
        ]);
    }

    public function edit(PropertyAttribute $propertyAttribute): View
    {
        $categories = PropertyAttributeCategory::where('active', 1)
            ->orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
        
        return view('admin.crud.edit', [
            'title' => 'Ingatlan attribútum szerkesztése',
            'pageTitle' => 'Ingatlan attribútum szerkesztése',
            'item' => $propertyAttribute,
            'fields' => $this->getFields($categories),
            'updateRoute' => 'admin.property-attributes.update',
            'indexRoute' => 'admin.property-attributes.index',
        ]);
    }

    public function update(PropertyAttributeRequest $request, PropertyAttribute $propertyAttribute): RedirectResponse
    {
        $data = $request->validated();
        $data['required'] = $request->has('required') ? 1 : 0;
        $data['show_in_search'] = $request->has('show_in_search') ? 1 : 0;
        $data['show_in_list'] = $request->has('show_in_list') ? 1 : 0;
        $data['active'] = $request->has('active') ? 1 : 0;
        
        $propertyAttribute->update($data);
        
        return redirect()->route('admin.property-attributes.index')
            ->with('success', 'Ingatlan attribútum sikeresen frissítve.');
    }

    public function destroy(PropertyAttribute $propertyAttribute): RedirectResponse
    {
        $propertyAttribute->delete();
        
        return redirect()->route('admin.property-attributes.index')
            ->with('success', 'Ingatlan attribútum sikeresen törölve.');
    }

    private function getFields(array $categories): array
    {
        return [
            ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
            ['name' => 'label', 'label' => 'Leírás', 'type' => 'text'],
            ['name' => 'short_label', 'label' => 'Rövid leírás', 'type' => 'text'],
            [
                'name' => 'property_attribute_category_id',
                'label' => 'Kategória',
                'type' => 'select',
                'options' => $categories,
                'required' => true,
            ],
            [
                'name' => 'type',
                'label' => 'Típus',
                'type' => 'select',
                'options' => [
                    'text' => 'Szövegdoboz',
                    'checkbox' => 'Jelölőnégyzet',
                    'select' => 'Legördülő lista',
                    'radio' => 'Választógomb',
                    'number' => 'Szám',
                ],
                'required' => true,
            ],
            ['name' => 'values', 'label' => 'Értékek', 'type' => 'text'],
            ['name' => 'suffix', 'label' => 'Utótag', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'prefix', 'label' => 'Előtag', 'type' => 'textarea', 'rows' => 3],
            ['name' => 'required', 'label' => 'Kötelező', 'type' => 'checkbox', 'checkbox_label' => 'Kötelező mező'],
            ['name' => 'show_in_search', 'label' => 'Keresésben megjelenik', 'type' => 'checkbox', 'checkbox_label' => 'Keresésben megjelenik', 'value' => true],
            ['name' => 'show_in_list', 'label' => 'Listázásban megjelenik', 'type' => 'checkbox', 'checkbox_label' => 'Listázásban megjelenik'],
            ['name' => 'active', 'label' => 'Aktív', 'type' => 'checkbox', 'checkbox_label' => 'Aktív', 'value' => true],
        ];
    }
}

