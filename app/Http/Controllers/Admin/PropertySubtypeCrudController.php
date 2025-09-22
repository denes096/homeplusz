<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertySubtypeRequest;
use App\Models\PropertyType;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class PropertySubtypeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\PropertySubtype::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/property-subtype');
        CRUD::setEntityNameStrings('ingatlan altípus', 'ingatlan altípusok');
    }

    protected function setupListOperation()
    {
        CRUD::addColumn([
            'label' => 'Főtípus',
            'type' => 'select',
            'name' => 'property_type_id',

            // optional
            'entity' => 'propertyType',
            'model' => PropertyType::class,
            'attribute' => 'name',
        ]);
        CRUD::column('name')->type('text')->label('Megnevezés');

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertySubtypeRequest::class);
        CRUD::addField([
            'label' => 'Főtípus',
            'type' => 'select',
            'name' => 'property_type_id',

            // optional
            'entity' => 'propertyType',
            'model' => PropertyType::class,
            'attribute' => 'name',
        ]);
        CRUD::field('name')->type('text')->label('Megnevezés');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
