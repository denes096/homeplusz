<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettlementRequest;
use App\Models\Settlement;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettlementController extends Controller
{
    public function index(): View
    {
        $settlements = Settlement::withCount('parts')->orderBy('name')->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Települések',
            'pageTitle' => 'Települések',
            'items' => $settlements,
            'columns' => [
                ['name' => 'postal_code', 'label' => 'Irányítószám'],
                ['name' => 'name', 'label' => 'Település neve'],
                [
                    'name' => 'parts_count',
                    'label' => 'Városrészek',
                    'type' => 'custom',
                    'value' => function($item) {
                        return '<a href="' . route('admin.settlement-parts.index', ['settlement' => $item->id]) . '">' . $item->parts_count . ' városrész</a>';
                    }
                ],
                ['name' => 'county', 'label' => 'Megye'],
            ],
            'createRoute' => 'admin.settlements.create',
            'editRoute' => 'admin.settlements.edit',
            'destroyRoute' => 'admin.settlements.destroy',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új település',
            'pageTitle' => 'Új település',
            'fields' => [
                ['name' => 'postal_code', 'label' => 'Irányítószám', 'type' => 'number', 'required' => true],
                ['name' => 'name', 'label' => 'Település neve', 'type' => 'text', 'required' => true],
                ['name' => 'county', 'label' => 'Megye', 'type' => 'text', 'required' => true],
            ],
            'storeRoute' => 'admin.settlements.store',
            'indexRoute' => 'admin.settlements.index',
        ]);
    }

    public function store(SettlementRequest $request): RedirectResponse
    {
        Settlement::create($request->validated());
        
        return redirect()->route('admin.settlements.index')
            ->with('success', 'Település sikeresen létrehozva.');
    }

    public function show(Settlement $settlement): View
    {
        $settlement->loadCount('parts');
        
        return view('admin.crud.show', [
            'title' => 'Település részletei',
            'pageTitle' => 'Település részletei',
            'item' => $settlement,
            'fields' => [
                ['name' => 'postal_code', 'label' => 'Irányítószám'],
                ['name' => 'name', 'label' => 'Település neve'],
                ['name' => 'county', 'label' => 'Megye'],
                ['name' => 'parts_count', 'label' => 'Városrészek száma'],
            ],
            'indexRoute' => 'admin.settlements.index',
            'editRoute' => 'admin.settlements.edit',
        ]);
    }

    public function edit(Settlement $settlement): View
    {
        return view('admin.crud.edit', [
            'title' => 'Település szerkesztése',
            'pageTitle' => 'Település szerkesztése',
            'item' => $settlement,
            'fields' => [
                ['name' => 'postal_code', 'label' => 'Irányítószám', 'type' => 'number', 'required' => true],
                ['name' => 'name', 'label' => 'Település neve', 'type' => 'text', 'required' => true],
                ['name' => 'county', 'label' => 'Megye', 'type' => 'text', 'required' => true],
            ],
            'updateRoute' => 'admin.settlements.update',
            'indexRoute' => 'admin.settlements.index',
        ]);
    }

    public function update(SettlementRequest $request, Settlement $settlement): RedirectResponse
    {
        $settlement->update($request->validated());
        
        return redirect()->route('admin.settlements.index')
            ->with('success', 'Település sikeresen frissítve.');
    }

    public function destroy(Settlement $settlement): RedirectResponse
    {
        $settlement->delete();
        
        return redirect()->route('admin.settlements.index')
            ->with('success', 'Település sikeresen törölve.');
    }
}

