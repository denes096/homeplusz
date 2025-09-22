<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyTypeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class PropertyTypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\PropertyType::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/property-type');
        CRUD::setEntityNameStrings('ingatlan típus', 'ingatlan típusok');
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->type('text')->label('Ingatlan típus');
        $this->crud->query->withCount('subtypes');
        $this->crud->addColumn([
            'label' => trans('Altípusok'),
            'type' => 'text',
            'name' => 'subtypes_count',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('property-subtype?property-type-id='.$entry->getKey());
                },
            ],
            'suffix' => ' '.strtolower(trans('altípus')),
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertyTypeRequest::class);
        CRUD::field('name')->type('text')->label('Ingatlan típus');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
