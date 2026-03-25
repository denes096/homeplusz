<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettlementPartRequest;
use App\Models\Settlement;
use App\Models\SettlementPart;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettlementPartController extends Controller
{
    public function index(Request $request): View
    {
        $query = SettlementPart::with('settlement');
        
        if ($request->has('settlement')) {
            $query->where('settlement_id', $request->get('settlement'));
        }
        
        $settlementParts = $query->orderBy('name')->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Településrészek',
            'pageTitle' => 'Településrészek',
            'items' => $settlementParts,
            'columns' => [
                [
                    'name' => 'settlement',
                    'label' => 'Település',
                    'type' => 'relationship',
                    'relationship' => 'settlement',
                    'attribute' => 'name',
                ],
                ['name' => 'name', 'label' => 'Név'],
            ],
            'createRoute' => 'admin.settlement-parts.create',
            'editRoute' => 'admin.settlement-parts.edit',
            'destroyRoute' => 'admin.settlement-parts.destroy',
        ]);
    }

    public function create(): View
    {
        $settlements = Settlement::orderBy('name')->get()->pluck('full_name', 'id')->toArray();
        
        return view('admin.crud.create', [
            'title' => 'Új településrész',
            'pageTitle' => 'Új településrész',
            'fields' => [
                [
                    'name' => 'settlement_id',
                    'label' => 'Település',
                    'type' => 'select',
                    'options' => $settlements,
                    'required' => true,
                ],
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
            ],
            'storeRoute' => 'admin.settlement-parts.store',
            'indexRoute' => 'admin.settlement-parts.index',
        ]);
    }

    public function store(SettlementPartRequest $request): RedirectResponse
    {
        SettlementPart::create($request->validated());
        
        return redirect()->route('admin.settlement-parts.index')
            ->with('success', 'Településrész sikeresen létrehozva.');
    }

    public function show(SettlementPart $settlementPart): View
    {
        $settlementPart->load('settlement');
        
        return view('admin.crud.show', [
            'title' => 'Településrész részletei',
            'pageTitle' => 'Településrész részletei',
            'item' => $settlementPart,
            'fields' => [
                [
                    'name' => 'settlement',
                    'label' => 'Település',
                    'type' => 'relationship',
                    'relationship' => 'settlement',
                    'attribute' => 'full_name',
                ],
                ['name' => 'name', 'label' => 'Név'],
            ],
            'indexRoute' => 'admin.settlement-parts.index',
            'editRoute' => 'admin.settlement-parts.edit',
        ]);
    }

    public function edit(SettlementPart $settlementPart): View
    {
        $settlements = Settlement::orderBy('name')->get()->pluck('full_name', 'id')->toArray();
        
        return view('admin.crud.edit', [
            'title' => 'Településrész szerkesztése',
            'pageTitle' => 'Településrész szerkesztése',
            'item' => $settlementPart,
            'fields' => [
                [
                    'name' => 'settlement_id',
                    'label' => 'Település',
                    'type' => 'select',
                    'options' => $settlements,
                    'required' => true,
                ],
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
            ],
            'updateRoute' => 'admin.settlement-parts.update',
            'indexRoute' => 'admin.settlement-parts.index',
        ]);
    }

    public function update(SettlementPartRequest $request, SettlementPart $settlementPart): RedirectResponse
    {
        $settlementPart->update($request->validated());
        
        return redirect()->route('admin.settlement-parts.index')
            ->with('success', 'Településrész sikeresen frissítve.');
    }

    public function destroy(SettlementPart $settlementPart): RedirectResponse
    {
        $settlementPart->delete();
        
        return redirect()->route('admin.settlement-parts.index')
            ->with('success', 'Településrész sikeresen törölve.');
    }
}

