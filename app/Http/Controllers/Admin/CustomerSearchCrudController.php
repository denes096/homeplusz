<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerSearchRequest;
use App\Models\Customers;
use App\Models\CustomerSearch;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerSearchCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerSearchCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CustomerSearch::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customer-search');
        CRUD::setEntityNameStrings('customer search', 'customer searches');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
         // set columns from db columns.
        CRUD::addField([   // select_grouped
            'label' => 'Vevő',
            'type' => 'select',
            'name' => 'customer_id',
            'entity' => 'customer',
            'attribute' => 'name_0',
            'model' => CustomerSearch::class,
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
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(CustomerSearchRequest::class);
        CRUD::addField([   // select_grouped
            'label' => 'Vevő',
            'type' => 'select',
            'name' => 'customer_id',
            'entity' => 'customer',
            'attribute' => 'name_0',
            'model' => Customers::class,
        ]);
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
