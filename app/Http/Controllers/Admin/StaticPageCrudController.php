<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\StaticPageRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class StaticPageCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class StaticPageCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\StaticPage::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/static-page');
        CRUD::setEntityNameStrings('statikus oldal', 'statikus oldalak');
    }

    protected function setupListOperation()
    {
        $this->crud->addColumns([
            [
                'label' => 'Slug',
                'type' => 'text',
                'name' => 'slug',
            ],
            [
                'label' => 'Tartalom',
                'type' => 'summernote',
                'name' => 'content',
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(StaticPageRequest::class);
        CRUD::field('slug')
            ->type('text')
            ->label('URL azonosító');

        CRUD::field('content')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Részletes leírás');
    }

    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }
}
