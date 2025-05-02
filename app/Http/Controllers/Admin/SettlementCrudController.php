<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettlementRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SettlementCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Settlement::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/settlement');
        CRUD::setEntityNameStrings('settlement', 'settlements');
    }

    protected function setupListOperation()
    {
        CRUD::column('postal_code')->type('text')->label('Irányítószám');
        CRUD::column('name')->type('text')->label('Település neve');
        CRUD::column('part')->type('text')->label('Település rész');

        $this->crud->query->withCount('parts');
        $this->crud->addColumn([
            'label' => trans('Városrészek'),
            'type' => 'text',
            'name' => 'parts_count',
            'wrapper' => [
                'href' => function ($crud, $column, $entry, $related_key) {
                    return backpack_url('settlement-part?settlement=' . $entry->getKey());
                },
            ],
            'suffix' => ' ' . strtolower(trans('városrész')),
        ]);


        CRUD::column('county')->type('text')->label('Megye');
        CRUD::column('area')->type('text')->label('Járás');

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SettlementRequest::class);
        CRUD::field('postal_code')->type('number')->label('Irányítószám');
        CRUD::field('name')->type('text')->label('Település neve');
        CRUD::field('part')->type('text')->label('Település rész');
        CRUD::field('county')->type('text')->label('Megye');
        CRUD::field('area')->type('text')->label('Járás');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
