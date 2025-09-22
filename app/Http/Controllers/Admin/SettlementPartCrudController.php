<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettlementPartRequest;
use App\Models\Settlement;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class SettlementPartCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\SettlementPart::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/settlement-part');
        CRUD::setEntityNameStrings('településrész', 'településrészek');
    }

    protected function setupListOperation()
    {
        CRUD::addColumn([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => 'Település',
            'type' => 'select',
            'name' => 'settlement_id',

            // optional
            'entity' => 'settlement', // the method that defines the relationship in your Model
            'model' => Settlement::class, // foreign key model
            'attribute' => 'name', // foreign key attribute that is shown to user
        ]);
        CRUD::column('name');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SettlementPartRequest::class);
        CRUD::addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => 'Település',
            'type' => 'select',
            'name' => 'settlement_id',

            // optional
            'entity' => 'settlement', // the method that defines the relationship in your Model
            'model' => Settlement::class, // foreign key model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'options' => (function ($query) {
                return $query->orderBy('name', 'ASC')->get();
            }),
        ]);
        CRUD::field('name');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
