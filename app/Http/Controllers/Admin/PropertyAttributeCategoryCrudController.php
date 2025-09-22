<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyAttributeCategoryRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class PropertyAttributeCategoryCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\PropertyAttributeCategory::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/property-attribute-category');
        CRUD::setEntityNameStrings('ingatlan attribútum kategória', 'ingatlan attribútum kategóriák');
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->type('text')->label('Megnevezés');
        CRUD::column('description')->type('text')->label('Leírás');
        CRUD::column('active')->type('checkbox')->label('Aktív');

    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertyAttributeCategoryRequest::class);
        CRUD::field('name')->type('text')->label('Megnevezés');
        CRUD::field('description')->type('text')->label('Leírás');
        CRUD::field('active')->type('checkbox')->label('Aktív');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
