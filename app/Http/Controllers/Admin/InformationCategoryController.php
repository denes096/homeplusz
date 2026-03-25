<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\InformationCategoryRequest;
use App\Models\InformationCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InformationCategoryController extends Controller
{
    public function index(): View
    {
        $categories = InformationCategory::latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Információ kategóriák',
            'pageTitle' => 'Információ kategóriák',
            'items' => $categories,
            'columns' => $this->getColumns(),
            'createRoute' => 'admin.information-categories.create',
            'editRoute' => 'admin.information-categories.edit',
            'destroyRoute' => 'admin.information-categories.destroy',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új információ kategória',
            'pageTitle' => 'Új információ kategória',
            'fields' => $this->getFields(),
            'storeRoute' => 'admin.information-categories.store',
            'indexRoute' => 'admin.information-categories.index',
        ]);
    }

    public function store(InformationCategoryRequest $request): RedirectResponse
    {
        InformationCategory::create($request->validated());
        
        return redirect()->route('admin.information-categories.index')
            ->with('success', 'Információ kategória sikeresen létrehozva.');
    }

    public function show(InformationCategory $informationCategory): View
    {
        return view('admin.crud.show', [
            'title' => 'Információ kategória részletei',
            'pageTitle' => 'Információ kategória részletei',
            'item' => $informationCategory,
            'fields' => $this->getShowFields(),
            'indexRoute' => 'admin.information-categories.index',
            'editRoute' => 'admin.information-categories.edit',
        ]);
    }

    public function edit(InformationCategory $informationCategory): View
    {
        return view('admin.crud.edit', [
            'title' => 'Információ kategória szerkesztése',
            'pageTitle' => 'Információ kategória szerkesztése',
            'item' => $informationCategory,
            'fields' => $this->getFields(),
            'updateRoute' => 'admin.information-categories.update',
            'indexRoute' => 'admin.information-categories.index',
        ]);
    }

    public function update(InformationCategoryRequest $request, InformationCategory $informationCategory): RedirectResponse
    {
        $informationCategory->update($request->validated());
        
        return redirect()->route('admin.information-categories.index')
            ->with('success', 'Információ kategória sikeresen frissítve.');
    }

    public function destroy(InformationCategory $informationCategory): RedirectResponse
    {
        $informationCategory->delete();
        
        return redirect()->route('admin.information-categories.index')
            ->with('success', 'Információ kategória sikeresen törölve.');
    }

    private function getColumns(): array
    {
        $model = new InformationCategory();
        $fillable = $model->getFillable();
        
        $columns = [];
        foreach ($fillable as $column) {
            $columns[] = ['name' => $column, 'label' => ucfirst(str_replace('_', ' ', $column))];
        }
        
        return $columns;
    }

    private function getFields(): array
    {
        $model = new InformationCategory();
        $fillable = $model->getFillable();
        
        $fields = [];
        foreach ($fillable as $column) {
            $type = $this->guessFieldType($column);
            $fields[] = [
                'name' => $column,
                'label' => ucfirst(str_replace('_', ' ', $column)),
                'type' => $type,
                'required' => in_array($column, ['name']),
            ];
        }
        
        return $fields;
    }

    private function getShowFields(): array
    {
        $model = new InformationCategory();
        $fillable = $model->getFillable();
        
        $fields = [];
        foreach ($fillable as $column) {
            $fields[] = [
                'name' => $column,
                'label' => ucfirst(str_replace('_', ' ', $column)),
            ];
        }
        
        return $fields;
    }

    private function guessFieldType(string $column): string
    {
        if (str_contains($column, 'description') || str_contains($column, 'content')) {
            return 'textarea';
        }
        
        if (str_contains($column, 'active') || str_contains($column, 'featured')) {
            return 'checkbox';
        }
        
        return 'text';
    }
}

