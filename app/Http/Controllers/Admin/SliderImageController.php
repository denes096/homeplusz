<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderImagesRequest;
use App\Models\SliderImages;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class SliderImageController extends Controller
{
    public function index(): View
    {
        $images = SliderImages::latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Slider képek',
            'pageTitle' => 'Slider képek',
            'items' => $images,
            'columns' => [
                [
                    'name' => 'path',
                    'label' => 'Kép',
                    'type' => 'custom',
                    'value' => function($item) {
                        if ($item->path) {
                            return '<img src="' . asset('storage/' . $item->path) . '" style="max-width: 100px; max-height: 100px; object-fit: cover;" alt="' . ($item->name ?? '') . '">';
                        }
                        return '-';
                    }
                ],
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'boolean'],
            ],
            'createRoute' => 'admin.slider-images.create',
            'editRoute' => 'admin.slider-images.edit',
            'destroyRoute' => 'admin.slider-images.destroy',
            'showRoute' => 'admin.slider-images.show',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új slider kép',
            'pageTitle' => 'Új slider kép',
            'fields' => [
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
                ['name' => 'path', 'label' => 'Kép', 'type' => 'file', 'accept' => 'image/*', 'required' => true],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'checkbox', 'checkbox_label' => 'Aktív', 'value' => true],
            ],
            'storeRoute' => 'admin.slider-images.store',
            'indexRoute' => 'admin.slider-images.index',
        ]);
    }

    public function store(SliderImagesRequest $request): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('path')) {
            $file = $request->file('path');
            $path = $file->store('uploads', 'public');
            $data['path'] = $path;
        }
        
        $data['active'] = $request->has('active') ? 1 : 0;
        
        SliderImages::create($data);
        
        return redirect()->route('admin.slider-images.index')
            ->with('success', 'Slider kép sikeresen létrehozva.');
    }

    public function show(SliderImages $sliderImage): View
    {
        return view('admin.crud.show', [
            'title' => 'Slider kép részletei',
            'pageTitle' => 'Slider kép részletei',
            'item' => $sliderImage,
            'fields' => [
                [
                    'name' => 'path',
                    'label' => 'Kép',
                    'type' => 'image',
                ],
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'boolean'],
            ],
            'indexRoute' => 'admin.slider-images.index',
            'editRoute' => 'admin.slider-images.edit',
        ]);
    }

    public function edit(SliderImages $sliderImage): View
    {
        return view('admin.crud.edit', [
            'title' => 'Slider kép szerkesztése',
            'pageTitle' => 'Slider kép szerkesztése',
            'item' => $sliderImage,
            'fields' => [
                ['name' => 'name', 'label' => 'Név', 'type' => 'text', 'required' => true],
                ['name' => 'path', 'label' => 'Kép', 'type' => 'file', 'accept' => 'image/*'],
                ['name' => 'active', 'label' => 'Aktív', 'type' => 'checkbox', 'checkbox_label' => 'Aktív'],
            ],
            'updateRoute' => 'admin.slider-images.update',
            'indexRoute' => 'admin.slider-images.index',
        ]);
    }

    public function update(SliderImagesRequest $request, SliderImages $sliderImage): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->hasFile('path')) {
            // Delete old image
            if ($sliderImage->path && Storage::disk('public')->exists($sliderImage->path)) {
                Storage::disk('public')->delete($sliderImage->path);
            }
            
            $file = $request->file('path');
            $path = $file->store('uploads', 'public');
            $data['path'] = $path;
        } else {
            // Keep existing path
            unset($data['path']);
        }
        
        $data['active'] = $request->has('active') ? 1 : 0;
        
        $sliderImage->update($data);
        
        return redirect()->route('admin.slider-images.index')
            ->with('success', 'Slider kép sikeresen frissítve.');
    }

    public function destroy(SliderImages $sliderImage): RedirectResponse
    {
        // Delete image file
        if ($sliderImage->path && Storage::disk('public')->exists($sliderImage->path)) {
            Storage::disk('public')->delete($sliderImage->path);
        }
        
        $sliderImage->delete();
        
        return redirect()->route('admin.slider-images.index')
            ->with('success', 'Slider kép sikeresen törölve.');
    }
}

