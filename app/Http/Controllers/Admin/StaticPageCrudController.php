<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StaticPageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StaticPageCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StaticPageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\StaticPage::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/static-page');
        CRUD::setEntityNameStrings('static page', 'static pages');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'label' => "Slug",
                'type' => 'text',
                'name' => 'slug',
            ],
            [
                'label' => "Tartalom",
                'type' => 'summernote',
                'name' => 'content',
            ]
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StaticPageRequest::class);
        CRUD::field('slug')
            ->type('text')
            ->label('Url slug');

        CRUD::field('content')
            ->type('textarea')
            ->attributes(['id' => 'ckeditor']) // ID, hogy felismerje
            ->label('Részletes leírás');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
