<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SliderImagesRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SliderImagesCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SliderImagesCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\SliderImages::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/slider-images');
        CRUD::setEntityNameStrings('slider images', 'slider images');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
    }

    /**
     * Define what happens when the Create operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation([
            'name' => 'required|string',
            'path' => 'required|file|image',
        ]);

        CRUD::addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => "Név",
            'type' => 'text',
            'name' => 'name', // the method that defines the relationship in your Model,
        ]);

        CRUD::field('path')
            ->type('upload')
            ->withFiles(
                [
                    'disk' => 'public', // the disk where file will be stored
                    'path' => 'uploads', // the path inside the disk where file will be stored
                ]
            )->attributes([
                'id' => 'input_images', // 💡 ID hozzáadása a JS miatt
            ]);

        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>');
        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
    }

    /**
     * Define what happens when the Update operation is loaded.
     *
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
