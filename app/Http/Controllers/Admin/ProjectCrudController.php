<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ProjectRequest;
use App\Models\UniqueCode;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class ProjectCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProjectCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Project::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/project');
        CRUD::setEntityNameStrings('project', 'projects');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::column('name');
        CRUD::column('title');
        CRUD::column('deadline');
        CRUD::column('contractor');
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
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'deadline' => 'required|date',
            'contractor' => 'required|string',
        ]);

        CRUD::addField([
            'label' => "Projekt azonosító",
            'type' => 'number',
            'name' => 'project_code',
            'value' => UniqueCode::getNextCode()
        ]);
        CRUD::field('name')->label("Név");
        CRUD::field('title')->label("Összefoglaló");
        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Leírás');

        CRUD::field('deadline')->type('date')->label("Határidő");
        CRUD::field('contractor')->type('text')->label("Kivitelező");
        CRUD::field('images')
            ->label("Képek")
            ->type('upload_multiple')
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
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
            ; // vagy bármi a tab neve;

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
        CRUD::setValidation([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'deadline' => 'required|date',
            'contractor' => 'required|string',
        ]);

        CRUD::addField([
            'label' => "Projekt azonosító",
            'type' => 'number',
            'name' => 'project_code'
        ]);
        CRUD::field('name')->label("Név");
        CRUD::field('title')->label("Összefoglaló");
        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Leírás');

        CRUD::field('deadline')->type('date')->label("Határidő");
        CRUD::field('contractor')->type('text')->label("Kivitelező");
        CRUD::field('images')
            ->label("Képek")
            ->type('upload_multiple')
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
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
        ; // vagy bármi a tab neve;

    }

    public function store()
    {

        $this->crud->hasAccessOrFail('create');

        // execute the FormRequest authorization and validation, if one is required
        $request = $this->crud->validateRequest();

        // register any Model Events defined on fields
        $this->crud->registerFieldEvents();

        // insert item in the db
        $itemAttributes = $this->crud->getStrippedSaveRequest($request);
        $item = $this->crud->create($itemAttributes);

        UniqueCode::updateCode((int)$itemAttributes['project_code']);

        $this->data['entry'] = $this->crud->entry = $item;

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();

        // frissítés maga
        $itemAttributes = $this->crud->getStrippedSaveRequest($request);
        $item = $this->crud->update(
            Route::current()->parameter($this->crud->getModel()->getRouteKeyName()),
            $itemAttributes
        );

        UniqueCode::updateCode((int)$itemAttributes['project_code']);

        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }
}
