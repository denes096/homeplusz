<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomerSearchRequest;
use App\Models\Customers;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomerSearchCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomerSearchCrudController extends CrudController
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
        CRUD::setModel(\App\Models\CustomerSearch::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/customer-search');
        CRUD::setEntityNameStrings('customer search', 'customer searches');
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

        // Use custom view for better display
        CRUD::setListView('vendor.backpack.customer-search.list');
    }

    /**
     * Override the list operation to pass entries to custom view
     */
    public function index()
    {
        $this->crud->hasAccessOrFail('list');

        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? mb_ucfirst($this->crud->entity_name_plural);

        // Get the model and paginate with customer relationship
        $model = $this->crud->model;
        $this->data['entries'] = $model->with('customer')->paginate(15);

        // load the view from /resources/views/vendor/backpack/crud/ if it exists, otherwise load the one in the package
        return view($this->crud->getListView(), $this->data);
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
        CRUD::setValidation(CustomerSearchRequest::class);

        CRUD::addField([
            'label' => 'Vevő',
            'type' => 'select',
            'name' => 'customer_id',
            'entity' => 'customer',
            'attribute' => 'name_0',
            'model' => Customers::class,
            'allows_null' => false,
        ]);

        CRUD::addField([
            'name' => 'search',
            'label' => 'Keresési paraméterek (JSON)',
            'type' => 'textarea',
            'hint' => 'Adja meg a keresési paramétereket JSON formátumban. Például: {"p[price_min]": 100000, "p[price_max]": 500000, "p[property_types]": [1,2]}',
            'attributes' => [
                'rows' => 10,
                'placeholder' => '{"p[price_min]": 100000, "p[price_max]": 500000, "p[property_types]": [1,2]}',
            ],
        ]);
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
