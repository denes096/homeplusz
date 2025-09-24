<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ServiceRequest;
use App\Models\ServiceCategory;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ServiceCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ServiceCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Service::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/service');
        CRUD::setEntityNameStrings('service', 'services');
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
        CRUD::column('name')
            ->label('Szolgáltatás neve')
            ->type('text');

        CRUD::column('service_category_id')
            ->label('Kategória')
            ->type('select')
            ->entity('serviceCategory')
            ->model(ServiceCategory::class)
            ->attribute('name');

        CRUD::column('description')
            ->label('Leírás')
            ->type('text')
            ->limit(100);

        CRUD::column('featured')
            ->label('Kiemelt')
            ->type('boolean');

        CRUD::column('formatted_icon')
            ->label('Ikon')
            ->type('closure')
            ->function(function ($entry) {
                if ($entry->formatted_icon) {
                    return '<i class="' . $entry->formatted_icon . '"></i> ' . $entry->icon;
                }
                return '-';
            });

        CRUD::column('created_at')
            ->label('Létrehozva')
            ->type('datetime');
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
        CRUD::setValidation(ServiceRequest::class);

        CRUD::field('name')
            ->label('Szolgáltatás neve')
            ->type('text');

        CRUD::field('service_category_id')
            ->label('Szolgáltatás kategória')
            ->type('select')
            ->entity('serviceCategory')
            ->model(ServiceCategory::class)
            ->attribute('name');

        CRUD::field('description')
            ->label('Leírás')
            ->type('textarea');

        CRUD::field('featured')
            ->label('Kiemelt')
            ->type('boolean');

        CRUD::field('icon')
            ->label('Ikon')
            ->type('text')
            ->hint('Font Awesome ikon osztály (pl: fas fa-home)')
            ->placeholder('fas fa-home');
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
        $this->setupCreateOperation();
    }
}
