<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StaticPageRequest;
use App\Models\StaticPage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class StaticPageController extends Controller
{
    public function index(): View
    {
        $pages = StaticPage::latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Statikus oldalak',
            'pageTitle' => 'Statikus oldalak',
            'items' => $pages,
            'columns' => [
                ['name' => 'slug', 'label' => 'URL azonosító'],
                [
                    'name' => 'content',
                    'label' => 'Tartalom',
                    'type' => 'custom',
                    'value' => function($item) {
                        return \Str::limit(strip_tags($item->content ?? ''), 100);
                    }
                ],
            ],
            'createRoute' => 'admin.static-pages.create',
            'editRoute' => 'admin.static-pages.edit',
            'destroyRoute' => 'admin.static-pages.destroy',
            'showRoute' => 'admin.static-pages.show',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új statikus oldal',
            'pageTitle' => 'Új statikus oldal',
            'fields' => [
                ['name' => 'slug', 'label' => 'URL azonosító', 'type' => 'text', 'required' => true],
                ['name' => 'content', 'label' => 'Részletes leírás', 'type' => 'textarea', 'rows' => 15, 'required' => true],
            ],
            'storeRoute' => 'admin.static-pages.store',
            'indexRoute' => 'admin.static-pages.index',
        ]);
    }

    public function store(StaticPageRequest $request): RedirectResponse
    {
        StaticPage::create($request->validated());
        
        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Statikus oldal sikeresen létrehozva.');
    }

    public function show(StaticPage $staticPage): View
    {
        return view('admin.crud.show', [
            'title' => 'Statikus oldal részletei',
            'pageTitle' => 'Statikus oldal részletei',
            'item' => $staticPage,
            'fields' => [
                ['name' => 'slug', 'label' => 'URL azonosító'],
                ['name' => 'content', 'label' => 'Tartalom'],
            ],
            'indexRoute' => 'admin.static-pages.index',
            'editRoute' => 'admin.static-pages.edit',
        ]);
    }

    public function edit(StaticPage $staticPage): View
    {
        return view('admin.crud.edit', [
            'title' => 'Statikus oldal szerkesztése',
            'pageTitle' => 'Statikus oldal szerkesztése',
            'item' => $staticPage,
            'fields' => [
                ['name' => 'slug', 'label' => 'URL azonosító', 'type' => 'text', 'required' => true],
                ['name' => 'content', 'label' => 'Részletes leírás', 'type' => 'textarea', 'rows' => 15, 'required' => true],
            ],
            'updateRoute' => 'admin.static-pages.update',
            'indexRoute' => 'admin.static-pages.index',
        ]);
    }

    public function update(StaticPageRequest $request, StaticPage $staticPage): RedirectResponse
    {
        $staticPage->update($request->validated());
        
        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Statikus oldal sikeresen frissítve.');
    }

    public function destroy(StaticPage $staticPage): RedirectResponse
    {
        $staticPage->delete();
        
        return redirect()->route('admin.static-pages.index')
            ->with('success', 'Statikus oldal sikeresen törölve.');
    }
}

