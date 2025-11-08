<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\InformationRequest;
use App\Models\InformationCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class InformationCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class InformationCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Information::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/information');
        CRUD::setEntityNameStrings('információ', 'információk');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     *
     * @return void
     */
    protected function setupListOperation()
    {
        // Name column
        CRUD::column('name')
            ->type('text')
            ->label('Név');

        // Category column
        CRUD::column('informationCategory.name')
            ->type('text')
            ->label('Kategória')
            ->entity('informationCategory')
            ->attribute('name');

        // Description column (show only first 100 characters)
        CRUD::column('description')
            ->type('text')
            ->label('Leírás')
            ->limit(100);

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     *
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(InformationRequest::class);

        // Name field
        CRUD::field('name')
            ->type('text')
            ->label('Név')
            ->tab('Alapadatok')
            ->wrapperAttributes(['class' => 'col-12 col-md-6']);

        // Category field
        CRUD::addField([
            'label' => 'Kategória',
            'type' => 'select',
            'name' => 'information_category_id',
            'entity' => 'informationCategory',
            'attribute' => 'name',
            'model' => InformationCategory::class,
            'tab' => 'Alapadatok',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6'],
        ]);

        // Description field with CKEditor
        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor form-control'])
            ->label('Leírás')
            ->tab('Alapadatok')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     *
     * @return void
     */
    protected function setupUpdateOperation()
    {
        CRUD::setValidation(InformationRequest::class);

        // Name field
        CRUD::field('name')
            ->type('text')
            ->label('Név')
            ->tab('Alapadatok')
            ->wrapperAttributes(['class' => 'col-12 col-md-6']);

        // Category field
        CRUD::addField([
            'label' => 'Kategória',
            'type' => 'select',
            'name' => 'information_category_id',
            'entity' => 'informationCategory',
            'attribute' => 'name',
            'model' => InformationCategory::class,
            'tab' => 'Alapadatok',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6'],
        ]);

        // Description field with CKEditor
        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor form-control'])
            ->label('Leírás')
            ->tab('Alapadatok')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);
    }
}
