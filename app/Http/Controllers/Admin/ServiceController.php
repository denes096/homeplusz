<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::with('serviceCategory')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Szolgáltatások',
            'pageTitle' => 'Szolgáltatások',
            'items' => $services,
            'columns' => [
                ['name' => 'name', 'label' => 'Szolgáltatás neve'],
                [
                    'name' => 'serviceCategory',
                    'label' => 'Kategória',
                    'type' => 'relationship',
                    'relationship' => 'serviceCategory',
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
                ['name' => 'featured', 'label' => 'Kiemelt', 'type' => 'boolean'],
                ['name' => 'created_at', 'label' => 'Létrehozva'],
            ],
            'createRoute' => 'admin.services.create',
            'editRoute' => 'admin.services.edit',
            'destroyRoute' => 'admin.services.destroy',
            'showRoute' => 'admin.services.show',
        ]);
    }

    public function create(): View
    {
        $categories = ServiceCategory::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.crud.create', [
            'title' => 'Új szolgáltatás',
            'pageTitle' => 'Új szolgáltatás',
            'fields' => [
                ['name' => 'name', 'label' => 'Szolgáltatás neve', 'type' => 'text', 'required' => true],
                [
                    'name' => 'service_category_id',
                    'label' => 'Szolgáltatás kategória',
                    'type' => 'select',
                    'options' => $categories,
                    'required' => true,
                ],
                ['name' => 'description', 'label' => 'Leírás', 'type' => 'textarea', 'rows' => 10],
                ['name' => 'featured', 'label' => 'Kiemelt', 'type' => 'checkbox', 'checkbox_label' => 'Kiemelt szolgáltatás'],
            ],
            'storeRoute' => 'admin.services.store',
            'indexRoute' => 'admin.services.index',
        ]);
    }

    public function store(ServiceRequest $request): RedirectResponse
    {
        Service::create($request->validated());
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Szolgáltatás sikeresen létrehozva.');
    }

    public function show(Service $service): View
    {
        $service->load('serviceCategory');
        
        return view('admin.crud.show', [
            'title' => 'Szolgáltatás részletei',
            'pageTitle' => 'Szolgáltatás részletei',
            'item' => $service,
            'fields' => [
                ['name' => 'name', 'label' => 'Szolgáltatás neve'],
                [
                    'name' => 'serviceCategory',
                    'label' => 'Kategória',
                    'type' => 'relationship',
                    'relationship' => 'serviceCategory',
                    'attribute' => 'name',
                ],
                ['name' => 'description', 'label' => 'Leírás'],
                ['name' => 'featured', 'label' => 'Kiemelt', 'type' => 'boolean'],
                ['name' => 'created_at', 'label' => 'Létrehozva'],
            ],
            'indexRoute' => 'admin.services.index',
            'editRoute' => 'admin.services.edit',
        ]);
    }

    public function edit(Service $service): View
    {
        $categories = ServiceCategory::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.crud.edit', [
            'title' => 'Szolgáltatás szerkesztése',
            'pageTitle' => 'Szolgáltatás szerkesztése',
            'item' => $service,
            'fields' => [
                ['name' => 'name', 'label' => 'Szolgáltatás neve', 'type' => 'text', 'required' => true],
                [
                    'name' => 'service_category_id',
                    'label' => 'Szolgáltatás kategória',
                    'type' => 'select',
                    'options' => $categories,
                    'required' => true,
                ],
                ['name' => 'description', 'label' => 'Leírás', 'type' => 'textarea', 'rows' => 10],
                ['name' => 'featured', 'label' => 'Kiemelt', 'type' => 'checkbox', 'checkbox_label' => 'Kiemelt szolgáltatás'],
            ],
            'updateRoute' => 'admin.services.update',
            'indexRoute' => 'admin.services.index',
        ]);
    }

    public function update(ServiceRequest $request, Service $service): RedirectResponse
    {
        $service->update($request->validated());
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Szolgáltatás sikeresen frissítve.');
    }

    public function destroy(Service $service): RedirectResponse
    {
        $service->delete();
        
        return redirect()->route('admin.services.index')
            ->with('success', 'Szolgáltatás sikeresen törölve.');
    }
}

