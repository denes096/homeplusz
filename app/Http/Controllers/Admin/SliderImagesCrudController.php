<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SliderImagesCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SliderImagesCrudController extends CrudController
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
        CRUD::setModel(\App\Models\SliderImages::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/slider-images');
        CRUD::setEntityNameStrings('slider kép', 'slider képek');
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
        CRUD::setFromDb(); // set columns from db columns.

        // Add image preview column
        CRUD::column('path')
            ->label('Kép')
            ->type('custom_html')
            ->value(function ($entry) {
                return '<img src="'.asset('storage/'.$entry->path).'" style="max-width: 100px; max-height: 100px; object-fit: cover;" alt="'.$entry->name.'">';
            });

        // Add active status column
        CRUD::column('active')
            ->label('Aktív')
            ->type('boolean')
            ->options([0 => 'Inaktív', 1 => 'Aktív'])
            ->wrapper([
                'class' => 'text-center',
            ]);

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
        CRUD::setValidation(\App\Http\Requests\SliderImagesRequest::class);

        CRUD::addField([
            'label' => 'Név',
            'type' => 'text',
            'name' => 'name',
        ]);

        CRUD::addField([
            'label' => 'Aktív',
            'type' => 'boolean',
            'name' => 'active',
            'default' => true,
            'wrapper' => ['class' => 'form-group col-md-6'],
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

        // Add image preview for create operation
        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>');
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
        // Use the same request class for validation - it handles create vs update differently
        CRUD::setValidation(\App\Http\Requests\SliderImagesRequest::class);

        CRUD::addField([
            'label' => 'Név',
            'type' => 'text',
            'name' => 'name',
        ]);

        CRUD::addField([
            'label' => 'Aktív',
            'type' => 'boolean',
            'name' => 'active',
            'wrapper' => ['class' => 'form-group col-md-6'],
        ]);

        // Get current entry for image preview
        $entry = $this->crud->getCurrentEntry();
        $imagePreviewHtml = '';

        if ($entry && $entry->path) {
            $imagePreviewHtml = '<div style="margin-bottom: 20px;">
                <label>Jelenlegi kép:</label><br>
                <img src="'.asset('storage/'.$entry->path).'" style="max-width: 300px; max-height: 200px; object-fit: cover; border: 2px solid #ddd; border-radius: 5px;" alt="'.$entry->name.'">
            </div>';
        }

        CRUD::field('path')
            ->type('upload')
            ->withFiles(
                [
                    'disk' => 'public',
                    'path' => 'uploads',
                ]
            )->attributes([
                'id' => 'input_images',
            ]);

        // Add current image preview for update operation
        CRUD::field('current_image_preview')
            ->type('custom_html')
            ->value($imagePreviewHtml);

        // Add image preview helper
        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>');
    }
}
