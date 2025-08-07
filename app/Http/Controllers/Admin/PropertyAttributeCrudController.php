<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyAttributeRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

class PropertyAttributeCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\PropertyAttribute::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/property-attribute');
        CRUD::setEntityNameStrings('property attribute', 'property attributes');
    }

    protected function setupListOperation()
    {
        CRUD::column('name')->label('Név')->type('text');
        CRUD::column('label')->label('Leírás')->type('text');
        CRUD::column('short_label')->label('Rövid leírás')->type('text');
        CRUD::addColumn([  // Select
            'label'     => "Kategória",
            'type'      => 'select',
            'name'      => 'property_attribute_category_id', // the db column for the foreign key
            'entity'    => 'category',
            'model'     => "App\Models\PropertyAttributeCategory", // related model
            'attribute' => 'name', // foreign key attribute that is shown to user
        ]);
        CRUD::addColumn([
            'name'  => 'type',
            'label' => 'Type',
            'type'  => 'enum',
            'options' => [
                'text' => 'Textbox',
                'checkbox' => 'Checkbox',
                'select' => 'Select',
                'radio' => 'Radio',
                'number' => 'Number'
            ]
        ]);
        CRUD::column('values')->label('Value(s)')->type('text');
        CRUD::column('suffix')->label('suffix')->type('text');
        CRUD::column('prefix')->label('prefix')->type('text');
        CRUD::column('required')->label('Required')->type('checkbox');
        CRUD::column('show_in_search')->label('Show in search')->type('checkbox')->default(true);
        CRUD::column('show_in_list')->label('Show in listing')->type('checkbox')->default(false);
        CRUD::column('active')->label('Active')->type('checkbox')->default(true);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertyAttributeRequest::class);

        CRUD::field('name')->label('Név')->type('text');
        CRUD::field('label')->label('Label')->type('text');
        CRUD::field('short_label')->label('Rövid leírás')->type('text');
        CRUD::addField([  // Select
            'label'     => "Category",
            'type'      => 'select',
            'name'      => 'property_attribute_category_id',
            'model'     => "App\Models\PropertyAttributeCategory",
            'attribute' => 'name',
            'options'   => (function ($query) {
                return $query->orderBy('name', 'ASC')->where('active', 1)->get();
            }),
        ]);

        CRUD::addField([
            'name'  => 'type',
            'label' => 'Type',
            'type'  => 'enum',
            'options' => [
                'text' => 'Textbox',
                'checkbox' => 'Checkbox',
                'select' => 'Select',
                'radio' => 'Radio',
                'number' => 'Number'
            ]
        ]);
        CRUD::field('values')->label('Value(s)')->type('text');
        CRUD::field('suffix')->label('suffix')->type('summernote');
        CRUD::field('prefix')->label('prefix')->type('summernote');
        CRUD::field('required')->label('Required')->type('checkbox');
        CRUD::field('show_in_search')->label('Show in search')->type('checkbox')->default(true);
        CRUD::field('show_in_list')->label('Show in listing')->type('checkbox')->default(false);
        CRUD::field('active')->label('Active')->type('checkbox')->default(true);

    }


    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

}
