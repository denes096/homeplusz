<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceCategoryRequest;
use App\Models\ServiceCategory;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ServiceCategoryController extends Controller
{
    public function index(): View
    {
        $categories = ServiceCategory::latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Szolgáltatás kategóriák',
            'pageTitle' => 'Szolgáltatás kategóriák',
            'items' => $categories,
            'columns' => $this->getColumns(),
            'createRoute' => 'admin.service-categories.create',
            'editRoute' => 'admin.service-categories.edit',
            'destroyRoute' => 'admin.service-categories.destroy',
        ]);
    }

    public function create(): View
    {
        return view('admin.crud.create', [
            'title' => 'Új szolgáltatás kategória',
            'pageTitle' => 'Új szolgáltatás kategória',
            'fields' => $this->getFields(),
            'storeRoute' => 'admin.service-categories.store',
            'indexRoute' => 'admin.service-categories.index',
        ]);
    }

    public function store(ServiceCategoryRequest $request): RedirectResponse
    {
        ServiceCategory::create($request->validated());
        
        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Szolgáltatás kategória sikeresen létrehozva.');
    }

    public function show(ServiceCategory $serviceCategory): View
    {
        return view('admin.crud.show', [
            'title' => 'Szolgáltatás kategória részletei',
            'pageTitle' => 'Szolgáltatás kategória részletei',
            'item' => $serviceCategory,
            'fields' => $this->getShowFields(),
            'indexRoute' => 'admin.service-categories.index',
            'editRoute' => 'admin.service-categories.edit',
        ]);
    }

    public function edit(ServiceCategory $serviceCategory): View
    {
        return view('admin.crud.edit', [
            'title' => 'Szolgáltatás kategória szerkesztése',
            'pageTitle' => 'Szolgáltatás kategória szerkesztése',
            'item' => $serviceCategory,
            'fields' => $this->getFields(),
            'updateRoute' => 'admin.service-categories.update',
            'indexRoute' => 'admin.service-categories.index',
        ]);
    }

    public function update(ServiceCategoryRequest $request, ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->update($request->validated());
        
        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Szolgáltatás kategória sikeresen frissítve.');
    }

    public function destroy(ServiceCategory $serviceCategory): RedirectResponse
    {
        $serviceCategory->delete();
        
        return redirect()->route('admin.service-categories.index')
            ->with('success', 'Szolgáltatás kategória sikeresen törölve.');
    }

    private function getColumns(): array
    {
        // Get all fillable columns from the model
        $model = new ServiceCategory();
        $fillable = $model->getFillable();
        
        $columns = [];
        foreach ($fillable as $column) {
            $columns[] = ['name' => $column, 'label' => ucfirst(str_replace('_', ' ', $column))];
        }
        
        return $columns;
    }

    private function getFields(): array
    {
        $model = new ServiceCategory();
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
        $model = new ServiceCategory();
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

