<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationRequest;
use App\Models\Information;
use App\Models\InformationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InformationController extends Controller
{
    public function index(): View
    {
        $informations = Information::with('informationCategory')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Információk',
            'pageTitle' => 'Információk',
            'items' => $informations,
            'columns' => [
                ['name' => 'name', 'label' => 'Név'],
                [
                    'name' => 'informationCategory',
                    'label' => 'Kategória',
                    'type' => 'relationship',
                    'relationship' => 'informationCategory',
                    'attribute' => 'name',
                ],
                [
                    'name' => 'description',
                    'label' => 'Leírás',
                    'type' => 'custom',
                    'value' => function($item) {
                        return \Str::limit(strip_tags($item->description ?? ''), 100);
                    }
                ],
            ],
            'createRoute' => 'admin.information.create',
            'editRoute' => 'admin.information.edit',
            'destroyRoute' => 'admin.information.destroy',
            'showRoute' => 'admin.information.show',
        ]);
    }

    public function create(): View
    {
        $categories = InformationCategory::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.crud.create', [
            'title' => 'Új információ',
            'pageTitle' => 'Új információ',
            'fields' => [
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
                [
                    'name' => 'information_category_id',
                    'label' => 'Kategória',
                    'type' => 'select',
                    'options' => $categories,
                    'required' => true,
                ],
                ['name' => 'description', 'label' => 'Leírás', 'type' => 'textarea', 'rows' => 10],
            ],
            'storeRoute' => 'admin.information.store',
            'indexRoute' => 'admin.information.index',
        ]);
    }

    public function store(InformationRequest $request): RedirectResponse
    {
        Information::create($request->validated());
        
        return redirect()->route('admin.information.index')
            ->with('success', 'Információ sikeresen létrehozva.');
    }

    public function show(Information $information): View
    {
        $information->load('informationCategory');
        
        return view('admin.crud.show', [
            'title' => 'Információ részletei',
            'pageTitle' => 'Információ részletei',
            'item' => $information,
            'fields' => [
                ['name' => 'name', 'label' => 'Név'],
                [
                    'name' => 'informationCategory',
                    'label' => 'Kategória',
                    'type' => 'relationship',
                    'relationship' => 'informationCategory',
                    'attribute' => 'name',
                ],
                ['name' => 'description', 'label' => 'Leírás'],
            ],
            'indexRoute' => 'admin.information.index',
            'editRoute' => 'admin.information.edit',
        ]);
    }

    public function edit(Information $information): View
    {
        $categories = InformationCategory::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.crud.edit', [
            'title' => 'Információ szerkesztése',
            'pageTitle' => 'Információ szerkesztése',
            'item' => $information,
            'fields' => [
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
                [
                    'name' => 'information_category_id',
                    'label' => 'Kategória',
                    'type' => 'select',
                    'options' => $categories,
                    'required' => true,
                ],
                ['name' => 'description', 'label' => 'Leírás', 'type' => 'textarea', 'rows' => 10],
            ],
            'updateRoute' => 'admin.information.update',
            'indexRoute' => 'admin.information.index',
        ]);
    }

    public function update(InformationRequest $request, Information $information): RedirectResponse
    {
        $information->update($request->validated());
        
        return redirect()->route('admin.information.index')
            ->with('success', 'Információ sikeresen frissítve.');
    }

    public function destroy(Information $information): RedirectResponse
    {
        $information->delete();
        
        return redirect()->route('admin.information.index')
            ->with('success', 'Információ sikeresen törölve.');
    }
}

