<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\LabelRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class LabelCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Label::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/label');
        CRUD::setEntityNameStrings('címke', 'címkék');
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->type('text')->label('Név');
        CRUD::column('color')->type('color')->label('Szín');
        CRUD::column('filter')->type('checkbox')->label('Főoldalon megjelenik');
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(LabelRequest::class);
        CRUD::field('name')->type('text')->label('Név');
        CRUD::field('color')->type('color')->label('Szín');
        CRUD::field('filter')->type('checkbox')->label('Főoldalon megjelenik');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
