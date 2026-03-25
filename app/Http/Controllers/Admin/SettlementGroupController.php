<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SettlementGroupRequest;
use App\Models\Settlement;
use App\Models\SettlementGroup;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class SettlementGroupController extends Controller
{
    public function index(): View
    {
        $settlementGroups = SettlementGroup::with(['settlement', 'settlements'])->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Településcsoportok',
            'pageTitle' => 'Településcsoportok',
            'items' => $settlementGroups,
            'columns' => [
                [
                    'name' => 'settlement',
                    'label' => 'Település',
                    'type' => 'relationship',
                    'relationship' => 'settlement',
                    'attribute' => 'full_name',
                ],
                [
                    'name' => 'settlements',
                    'label' => 'Környék',
                    'type' => 'custom',
                    'value' => function($item) {
                        return $item->settlements->pluck('full_name')->join(', ');
                    }
                ],
            ],
            'createRoute' => 'admin.settlement-groups.create',
            'editRoute' => 'admin.settlement-groups.edit',
            'destroyRoute' => 'admin.settlement-groups.destroy',
        ]);
    }

    public function create(): View
    {
        $settlements = Settlement::orderBy('name')->get()->pluck('full_name', 'id')->toArray();
        
        return view('admin.crud.create', [
            'title' => 'Új településcsoport',
            'pageTitle' => 'Új településcsoport',
            'fields' => [
                [
                    'name' => 'settlement_id',
                    'label' => 'Település',
                    'type' => 'select',
                    'options' => $settlements,
                    'required' => true,
                ],
                [
                    'name' => 'settlements',
                    'label' => 'Környék',
                    'type' => 'select_multiple',
                    'options' => $settlements,
                ],
            ],
            'storeRoute' => 'admin.settlement-groups.store',
            'indexRoute' => 'admin.settlement-groups.index',
        ]);
    }

    public function store(SettlementGroupRequest $request): RedirectResponse
    {
        $settlementGroup = SettlementGroup::create($request->only(['settlement_id']));
        
        if ($request->has('settlements')) {
            $settlementGroup->settlements()->sync($request->get('settlements'));
        }
        
        return redirect()->route('admin.settlement-groups.index')
            ->with('success', 'Településcsoport sikeresen létrehozva.');
    }

    public function show(SettlementGroup $settlementGroup): View
    {
        $settlementGroup->load(['settlement', 'settlements']);
        
        return view('admin.crud.show', [
            'title' => 'Településcsoport részletei',
            'pageTitle' => 'Településcsoport részletei',
            'item' => $settlementGroup,
            'fields' => [
                [
                    'name' => 'settlement',
                    'label' => 'Település',
                    'type' => 'relationship',
                    'relationship' => 'settlement',
                    'attribute' => 'full_name',
                ],
                [
                    'name' => 'settlements',
                    'label' => 'Környék',
                    'type' => 'custom',
                    'value' => function($item) {
                        return $item->settlements->pluck('full_name')->join(', ');
                    }
                ],
            ],
            'indexRoute' => 'admin.settlement-groups.index',
            'editRoute' => 'admin.settlement-groups.edit',
        ]);
    }

    public function edit(SettlementGroup $settlementGroup): View
    {
        $settlementGroup->load('settlements');
        $settlements = Settlement::orderBy('name')->get()->pluck('full_name', 'id')->toArray();
        $selectedSettlements = $settlementGroup->settlements->pluck('id')->toArray();
        
        return view('admin.crud.edit', [
            'title' => 'Településcsoport szerkesztése',
            'pageTitle' => 'Településcsoport szerkesztése',
            'item' => $settlementGroup,
            'fields' => [
                [
                    'name' => 'settlement_id',
                    'label' => 'Település',
                    'type' => 'select',
                    'options' => $settlements,
                    'required' => true,
                ],
                [
                    'name' => 'settlements',
                    'label' => 'Környék',
                    'type' => 'select_multiple',
                    'options' => $settlements,
                    'selected' => $selectedSettlements,
                ],
            ],
            'updateRoute' => 'admin.settlement-groups.update',
            'indexRoute' => 'admin.settlement-groups.index',
        ]);
    }

    public function update(SettlementGroupRequest $request, SettlementGroup $settlementGroup): RedirectResponse
    {
        $settlementGroup->update($request->only(['settlement_id']));
        
        if ($request->has('settlements')) {
            $settlementGroup->settlements()->sync($request->get('settlements'));
        } else {
            $settlementGroup->settlements()->sync([]);
        }
        
        return redirect()->route('admin.settlement-groups.index')
            ->with('success', 'Településcsoport sikeresen frissítve.');
    }

    public function destroy(SettlementGroup $settlementGroup): RedirectResponse
    {
        $settlementGroup->delete();
        
        return redirect()->route('admin.settlement-groups.index')
            ->with('success', 'Településcsoport sikeresen törölve.');
    }
}

