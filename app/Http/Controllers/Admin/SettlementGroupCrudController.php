<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SettlementGroupRequest;
use App\Models\Label;
use App\Models\Settlement;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SettlementGroupCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SettlementGroupCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     *
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\SettlementGroup::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/settlement-group');
        CRUD::setEntityNameStrings('settlement group', 'settlement groups');
    }

    /**
     * Define what happens when the List operation is loaded.
     *
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->addColumns([

            [
                'label'     => 'Település',
                'type'      => 'select',
                'name'      => 'settlement_id',
                'entity'    => 'settlement',
                'attribute' => 'fullName',
                'model'     => Settlement::class,
            ],
            [   // SelectMultiple = n-n relationship (with pivot table)
                'label'     => "Környék",
                'type'      => 'select_multiple',
                'name'      => 'settlements',
                // optional
                'entity'    => 'settlements',
                'model'     => Settlement::class,
                'attribute' => 'full_name',
            ],

        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(SettlementGroupRequest::class);
        $this->crud->addFields([
            [
                'label'     => 'Település',
                'type'      => 'select',
                'name'      => 'settlement_id',
                'entity'    => 'settlement',
                'attribute' => 'fullName',
                'model'     => Settlement::class,
            ],
            [   // SelectMultiple = n-n relationship (with pivot table)
                'label'     => "Környék",
                'type'      => 'select_multiple',
                'name'      => 'settlements',
                // optional
                'entity'    => 'settlements',
                'model'     => Settlement::class,
                'attribute' => 'full_name',
            ],

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
}
