<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\CustomersRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class CustomersCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomersCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Customers::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/customers');
        CRUD::setEntityNameStrings('customers', 'customers');
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
            'status' => 'required|in:Aktív,Felfüggesztve,Archív',
            'refId' => 'nullable|exists:users,id',
            'kategoria' => 'required|integer',
            'ekod' => 'required|string|max:20',
            'name_0' => 'required|string|max:100',
            'phone_0' => 'nullable|string|max:100',
            'azonosito1_0' => 'nullable|string|max:100',
            'azonosito2_0' => 'nullable|string|max:100',
            'name_1' => 'nullable|string|max:100',
            'phone_1' => 'nullable|string|max:100',
            'name_2' => 'nullable|string|max:100',
            'phone_2' => 'nullable|string|max:100',
            'name_3' => 'nullable|string|max:100',
            'phone_3' => 'nullable|string|max:100',
            'name_4' => 'nullable|string|max:100',
            'phone_4' => 'nullable|string|max:100',
            'email' => 'nullable|email|max:100',
            'note' => 'nullable|string|max:250',
        ]);

        CRUD::field('status')->type('select_from_array')->options([
            'Aktív' => 'Aktív',
            'Felfüggesztve' => 'Felfüggesztve',
            'Archív' => 'Archív',
        ])->default('Aktív')->tab('Alapadatok');

        CRUD::addField([
            'name' => 'refId',
            'tab' => 'Alapadatok',
            'label' => 'Referens',
            'type' => 'select',
            'entity' => 'referens', // kapcsolódó függvény a modelben
            'model' => 'App\Models\User',
            'attribute' => 'name', // vagy amit meg szeretnél jeleníteni
            'allows_null' => true,
        ]);

        CRUD::field('kategoria')->type('select_from_array')->options([
            '1' => '*',
            '2' => '**',
            '3' => '***',
            '4' => '****',
            '5' => '*****',
        ])->default('1')->tab('Alapadatok');

        CRUD::field('ekod')->type('text')->tab('Alapadatok');
        CRUD::field('name_0')->label('Név')->type('text')->tab('Alapadatok');
        CRUD::field('phone_0')->label('Telefonszám')->type('text')->tab('Alapadatok');
        CRUD::field('azonosito1_0')->label('Azonosító')->type('text')->tab('Alapadatok');
        //CRUD::field('azonosito2_0')->type('text')->tab('Alapadatok');
        //CRUD::field('name_1')->type('text')->tab('Alapadatok');
        //CRUD::field('phone_1')->type('text')->tab('Alapadatok');
        //CRUD::field('name_2')->type('text')->tab('Alapadatok');
        //CRUD::field('phone_2')->type('text')->tab('Alapadatok');
        //CRUD::field('name_3')->type('text')->tab('Alapadatok');
        //CRUD::field('phone_3')->type('text')->tab('Alapadatok');
//        CRUD::field('name_4')->type('text')->tab('Alapadatok');
//        CRUD::field('phone_4')->type('text')->tab('Alapadatok');
        CRUD::field('email')->type('email')->tab('Alapadatok');
        CRUD::field('note')->type('textarea')->tab('Alapadatok');


        CRUD::addField([
            'label' => 'Min ár',
            'type' => 'number',
            'name' => "p[min_ar]",
            'tab' => 'Keresési paraméterek',
        ]);

        CRUD::addField([
            'label' => 'Max ár',
            'name' => 'p[max_ar]',
            'type' => 'number',
            'tab' => 'Keresési paraméterek',
        ]);
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

        dd($request->get('p'));

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());

    }
}
