<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabelRequest;
use App\Models\Label;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LabelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $labels = Label::latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Címkék',
            'pageTitle' => 'Címkék',
            'items' => $labels,
            'columns' => [
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'color', 'label' => 'Szín', 'type' => 'color'],
                ['name' => 'filter', 'label' => 'Főoldalon megjelenik', 'type' => 'boolean'],
            ],
            'createRoute' => 'admin.labels.create',
            'editRoute' => 'admin.labels.edit',
            'destroyRoute' => 'admin.labels.destroy',
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új címke',
            'pageTitle' => 'Új címke',
            'fields' => [
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
                ['name' => 'color', 'label' => 'Szín', 'type' => 'color', 'required' => true],
                ['name' => 'filter', 'label' => 'Főoldalon megjelenik', 'type' => 'checkbox', 'checkbox_label' => 'Főoldalon megjelenik'],
            ],
            'storeRoute' => 'admin.labels.store',
            'indexRoute' => 'admin.labels.index',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LabelRequest $request): RedirectResponse
    {
        Label::create($request->validated());
        
        return redirect()->route('admin.labels.index')
            ->with('success', 'Címke sikeresen létrehozva.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Label $label): View
    {
        return view('admin.crud.show', [
            'title' => 'Címke részletei',
            'pageTitle' => 'Címke részletei',
            'item' => $label,
            'fields' => [
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'color', 'label' => 'Szín', 'type' => 'color'],
                ['name' => 'filter', 'label' => 'Főoldalon megjelenik', 'type' => 'boolean'],
            ],
            'indexRoute' => 'admin.labels.index',
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Label $label): View
    {
        return view('admin.crud.edit', [
            'title' => 'Címke szerkesztése',
            'pageTitle' => 'Címke szerkesztése',
            'item' => $label,
            'fields' => [
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
                ['name' => 'color', 'label' => 'Szín', 'type' => 'color', 'required' => true],
                ['name' => 'filter', 'label' => 'Főoldalon megjelenik', 'type' => 'checkbox', 'checkbox_label' => 'Főoldalon megjelenik'],
            ],
            'updateRoute' => 'admin.labels.update',
            'indexRoute' => 'admin.labels.index',
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LabelRequest $request, Label $label): RedirectResponse
    {
        $label->update($request->validated());
        
        return redirect()->route('admin.labels.index')
            ->with('success', 'Címke sikeresen frissítve.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Label $label): RedirectResponse
    {
        $label->delete();
        
        return redirect()->route('admin.labels.index')
            ->with('success', 'Címke sikeresen törölve.');
    }
}

