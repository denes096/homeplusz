<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyRequest;
use App\Models\Label;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyAttribute;
use App\Models\PropertyDocument;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\SettlementPart;
use App\Models\UniqueCode;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Class PropertyCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PropertyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Property::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/property');
        CRUD::setEntityNameStrings('ingatlan', 'ingatlanok');
        CRUD::removeButton('edit');
    }

    protected function setupFaszOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        // Filter to show only inactive properties
        $this->crud->addClause('where', 'is_active', false);

        // Remove default buttons
        $this->crud->removeButton('show');
        $this->crud->removeButton('edit');
        $this->crud->removeButton('delete'); // Remove delete button from listing

        // Add create button for new property
        CRUD::addButton('top', 'create', 'view', 'crud::buttons.create', 'beginning');

        // Add custom buttons
        $this->crud->addButtonFromModelFunction('line', 'showProperty', 'getShowButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'editProperty', 'getEditButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'toggleActive', 'getToggleActiveButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'matchingSearches', 'getMatchingSearchesButton', 'end');

        // Backpack CRUD handles search automatically via searchableTable
        // No need for custom search logic here

        $this->crud->addColumns([
            [
                'name' => 'first_image_url',
                'label' => 'Kép',
                'type' => 'image',

                'height' => '200px',
                'width' => '250px',
                'orderable' => false,
                'searchLogic' => false,
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('property_code', 'like', '%'.$searchTerm.'%');
                },
                'orderable' => true,
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text',
                'searchLogic' => false,
                'orderable' => true,
                'limit' => 50,
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'model_function',
                'function_name' => 'getFormattedPrice',
                'suffix' => ' Ft',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'ad_type',
                'label' => 'Típus',
                'type' => 'radio',
                'options' => [
                    'sell' => 'Eladó',
                    'rent' => 'Kiadó',
                ],
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'settlement.fullName',
                'label' => 'Település',
                'type' => 'text',
                'entity' => 'settlement',
                'attribute' => 'fullName',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'settlementPart.name',
                'label' => 'Településrész',
                'type' => 'text',
                'entity' => 'settlementPart',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'propertyType.name',
                'label' => 'Ingatlantípus',
                'type' => 'text',
                'entity' => 'propertyType',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'is_active',
                'label' => 'Aktív',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'user.name',
                'label' => 'Referens',
                'type' => 'text',
                'entity' => 'user',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'created_at',
                'label' => 'Létrehozva',
                'type' => 'datetime',
                'format' => 'Y-m-d H:i',
                'orderable' => true,
                'searchLogic' => false,
            ],
        ]);
    }

    protected function setupAktivOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        // Filter to show only active properties
        $this->crud->addClause('where', 'is_active', true);

        // Remove default buttons
        $this->crud->removeButton('show');
        $this->crud->removeButton('edit');
        $this->crud->removeButton('delete'); // Remove delete button from listing

        // Add create button for new property
        CRUD::addButton('top', 'create', 'view', 'crud::buttons.create', 'beginning');

        // Add custom buttons
        $this->crud->addButtonFromModelFunction('line', 'showProperty', 'getShowButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'editProperty', 'getEditButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'toggleActive', 'getToggleActiveButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'matchingSearches', 'getMatchingSearchesButton', 'end');

        // Backpack CRUD handles search automatically via searchableTable
        // No need for custom search logic here

        $this->crud->addColumns([
            [
                'name' => 'first_image_url',
                'label' => 'Kép',
                'type' => 'image',
                'height' => '200px',
                'width' => '250px',
                'orderable' => false,
                'searchLogic' => false,
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('property_code', 'like', '%'.$searchTerm.'%');
                },
                'orderable' => true,
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text',
                'searchLogic' => false,
                'orderable' => true,
                'limit' => 50,
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'model_function',
                'function_name' => 'getFormattedPrice',
                'suffix' => ' Ft',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'ad_type',
                'label' => 'Típus',
                'type' => 'radio',
                'options' => [
                    'sell' => 'Eladó',
                    'rent' => 'Kiadó',
                ],
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'settlement.fullName',
                'label' => 'Település',
                'type' => 'text',
                'entity' => 'settlement',
                'attribute' => 'fullName',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'settlementPart.name',
                'label' => 'Településrész',
                'type' => 'text',
                'entity' => 'settlementPart',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'propertyType.name',
                'label' => 'Ingatlantípus',
                'type' => 'text',
                'entity' => 'propertyType',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'is_active',
                'label' => 'Aktív',
                'type' => 'checkbox',
                'default' => true,
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'user.name',
                'label' => 'Referens',
                'type' => 'text',
                'entity' => 'user',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'created_at',
                'label' => 'Létrehozva',
                'type' => 'datetime',
                'format' => 'Y-m-d H:i',
                'orderable' => true,
                'searchLogic' => false,
            ],
        ]);
    }

    protected function setupFasztOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        // Filter to show only user's own properties
        $this->crud->addClause('where', 'user_id', auth()->user()->id);

        // Remove default buttons
        $this->crud->removeButton('show');
        $this->crud->removeButton('edit');
        $this->crud->removeButton('delete'); // Remove delete button from listing

        // Add create button for new property
        CRUD::addButton('top', 'create', 'view', 'crud::buttons.create', 'beginning');

        // Add custom buttons
        $this->crud->addButtonFromModelFunction('line', 'showProperty', 'getShowButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'editProperty', 'getEditButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'toggleActive', 'getToggleActiveButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'matchingSearches', 'getMatchingSearchesButton', 'end');

        // Backpack CRUD handles search automatically via searchableTable
        // No need for custom search logic here

        $this->crud->addColumns([
            [
                'name' => 'first_image_url',
                'label' => 'Kép',
                'type' => 'image',

                'height' => '200px',
                'width' => '250px',
                'orderable' => false,
                'searchLogic' => false,
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text',
                'searchLogic' => 'like',
                'orderable' => true,
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text',
                'searchLogic' => 'like',
                'orderable' => true,
                'limit' => 50,
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'model_function',
                'function_name' => 'getFormattedPrice',
                'suffix' => ' Ft',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'ad_type',
                'label' => 'Típus',
                'type' => 'radio',
                'options' => [
                    'sell' => 'Eladó',
                    'rent' => 'Kiadó',
                ],
                'orderable' => true,
                'searchLogic' => 'exact',
            ],
            [
                'name' => 'settlement.fullName',
                'label' => 'Település',
                'type' => 'text',
                'entity' => 'settlement',
                'attribute' => 'fullName',
                'orderable' => true,
                'searchLogic' => 'like',
            ],
            [
                'name' => 'settlementPart.name',
                'label' => 'Településrész',
                'type' => 'text',
                'entity' => 'settlementPart',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => 'like',
            ],
            [
                'name' => 'propertyType.name',
                'label' => 'Ingatlantípus',
                'type' => 'text',
                'entity' => 'propertyType',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => 'like',
            ],
            [
                'name' => 'is_active',
                'label' => 'Aktív',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => 'exact',
            ],
            [
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => 'exact',
            ],
            [
                'name' => 'user.name',
                'label' => 'Referens',
                'type' => 'text',
                'entity' => 'user',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => 'like',
            ],
            [
                'name' => 'created_at',
                'label' => 'Létrehozva',
                'type' => 'datetime',
                'format' => 'Y-m-d H:i',
                'orderable' => true,
                'searchLogic' => false,
            ],
        ]);
    }

    protected function setupListOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        // Filter to show only active properties
        // $this->crud->addClause('where', 'is_active', true);

        if (request()->has('user_id')) {
            $this->crud->addClause('where', 'user_id', request()->input('user_id'));
        }

        // Remove default buttons
        $this->crud->removeButton('show');
        $this->crud->removeButton('delete'); // Remove delete button from listing

        // Add create button for new property
        CRUD::addButton('top', 'create', 'view', 'crud::buttons.create', 'beginning');

        // Add custom buttons
        $this->crud->addButtonFromModelFunction('line', 'showProperty', 'getShowButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'editProperty', 'getEditButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'toggleActive', 'getToggleActiveButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'matchingSearches', 'getMatchingSearchesButton', 'end');
        $this->crud->removeButton('edit');

        // Backpack CRUD handles search automatically via searchableTable
        // No need for custom search logic here

        $this->crud->addColumns([
            [
                'name' => 'first_image_url',
                'label' => 'Kép',
                'type' => 'image',

                'height' => '200px',
                'width' => '250px',
                'orderable' => false,
                'searchLogic' => false,
                'wrapper' => [
                    'element' => 'div',
                    'style' => 'overflow: visible; height: auto; width: 150px;',
                ],
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text',
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhere('property_code', 'like', '%'.$searchTerm.'%');
                },
                'orderable' => true,
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text',
                'searchLogic' => false,
                'orderable' => true,
                'limit' => 50,
            ],
            [
                'name' => 'user.name',
                'model' => User::class,
                'label' => 'Referens',
                'type' => 'text',
                'entity' => 'user',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => function ($query, $column, $searchTerm) {
                    $query->orWhereHas('user', function ($query) use ($searchTerm) {
                        $query->where('name', 'like', '%'.$searchTerm.'%');
                    });
                },
            ],
            [
                'name' => 'client.name',
                'label' => 'Megbízó',
                'type' => 'text',
                'entity' => 'client',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'model_function',
                'function_name' => 'getFormattedPrice',
                'suffix' => ' Ft',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'ad_type',
                'label' => 'Típus',
                'type' => 'radio',
                'options' => [
                    'sell' => 'Eladó',
                    'rent' => 'Kiadó',
                ],
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'settlement.fullName',
                'label' => 'Település',
                'type' => 'text',
                'entity' => 'settlement',
                'attribute' => 'fullName',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'settlementPart.name',
                'label' => 'Településrész',
                'type' => 'text',
                'entity' => 'settlementPart',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'propertyType.name',
                'label' => 'Ingatlantípus',
                'type' => 'text',
                'entity' => 'propertyType',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'is_active',
                'label' => 'Aktív',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'user.name',
                'label' => 'Referens',
                'type' => 'text',
                'entity' => 'user',
                'attribute' => 'name',
                'orderable' => true,
                'searchLogic' => false,
            ],
            [
                'name' => 'created_at',
                'label' => 'Létrehozva',
                'type' => 'datetime',
                'format' => 'Y-m-d H:i',
                'orderable' => true,
                'searchLogic' => false,
            ],
        ]);
    }

    protected function setupShowOperation()
    {
        // Use custom view for property show
        $this->crud->set('show.view', 'vendor.backpack.crud.property_show');

        // Load all necessary relationships for the show view
        $this->crud->set('show.setFromDb', false);

        // Load relationships for the show view
        $this->crud->set('show.entry', function ($entry) {
            return $entry->load([
                'settlement',
                'settlementPart',
                'propertyType',
                'propertySubtype',
                'project',
                'client',
                'labels',
                'attributes.category',
            ]);
        });

        $this->crud->addColumns([
            [
                'name' => 'first_image_url',
                'label' => 'Kép',
                'type' => 'image',

                'height' => '300px',
                'width' => '400px',
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text',
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text',
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'model_function',
                'function_name' => 'getFormattedPrice',
                'suffix' => ' Ft',
            ],
            [
                'name' => 'ad_type',
                'label' => 'Típus',
                'type' => 'radio',
                'options' => [
                    'sell' => 'Eladó',
                    'rent' => 'Kiadó',
                ],
            ],
            [
                'name' => 'is_active',
                'label' => 'Aktív',
                'type' => 'checkbox',
            ],
            [
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
            ],
            [
                'name' => 'user.name',
                'label' => 'Referens',
                'type' => 'text',
                'entity' => 'user',
                'attribute' => 'name',
            ],
            [
                'name' => 'client.name',
                'label' => 'Megbízó',
                'type' => 'text',
                'entity' => 'client',
                'attribute' => 'name',
            ],
            [
                'name' => 'settlement.fullName',
                'label' => 'Település',
                'type' => 'text',
            ],
            [
                'name' => 'settlementPart.name',
                'label' => 'Településrész',
                'type' => 'text',
            ],
            [
                'name' => 'propertyType.name',
                'label' => 'Ingatlantípus',
                'type' => 'text',
            ],
            [
                'name' => 'propertySubtype.name',
                'label' => 'Ingatlan altípus',
                'type' => 'text',
            ],
            [
                'name' => 'project.name',
                'label' => 'Projekt',
                'type' => 'text',
            ],
            [
                'name' => 'address',
                'label' => 'Cím (térkép)',
                'type' => 'text',
            ],
            [
                'name' => 'latitude',
                'label' => 'Szélesség',
                'type' => 'number',
                'decimals' => 8,
            ],
            [
                'name' => 'longitude',
                'label' => 'Hosszúság',
                'type' => 'number',
                'decimals' => 8,
            ],
            [
                'name' => 'description',
                'label' => 'Részletes leírás',
                'type' => 'textarea',
            ],
            [
                'name' => 'inner_comments',
                'label' => 'Belső komment',
                'type' => 'textarea',
            ],
            [
                'name' => 'created_at',
                'label' => 'Létrehozva',
                'type' => 'datetime',
            ],
            [
                'name' => 'updated_at',
                'label' => 'Módosítva',
                'type' => 'datetime',
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        CRUD::addField([
            'name' => 'is_active',
            'label' => 'Aktív',
            'type' => 'checkbox',
            'default' => true,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'name' => 'featured',
            'label' => 'Kiemelt',
            'type' => 'checkbox',
            'default' => false,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::field('title')
            ->type('text')
            ->label('Cím')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);

        CRUD::field('price')
            ->type('number')
            ->label('Irányár')
            ->suffix('Ft')
            ->tab('Base')
            ->attributes([
                'step' => '0.01',
                'min' => '0',
            ])
            ->wrapperAttributes(['class' => 'col-12 col-md-6 col-lg-4']);

        CRUD::addField([
            'name' => 'ad_type',
            'label' => 'Típus',
            'type' => 'radio',
            'options' => [
                'sell' => 'Eladó',
                'rent' => 'Kiadó',
            ],
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);
        CRUD::addField([
            'label' => 'Projekt',
            'type' => 'select',
            'name' => 'project_id',
            'entity' => 'project',
            'attribute' => 'name',
            'model' => Project::class,
            'allows_null' => true,
            'default' => null,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Megbízó',
            'type' => 'select',
            'name' => 'client_id',
            'entity' => 'client',
            'attribute' => 'name',
            'model' => \App\Models\Client::class,
            'allows_null' => true,
            'default' => null,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Referens',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
            'allows_null' => true,
            'default' => backpack_user()->id,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);
        CRUD::addField([
            'label' => 'Ingatlan azonosító',
            'type' => 'text',
            'name' => 'property_code',
            'value' => UniqueCode::getNextCode(),
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'name' => 'custom_script',
            'type' => 'custom_html',
            'value' => '<script>
        document.addEventListener("DOMContentLoaded", function () {
            const projectSelect = document.querySelector("[name=project_id]");
            const codeInput = document.querySelector("[name=property_code]");

            if (projectSelect) {
                projectSelect.addEventListener("change", function () {
                    const projectId = this.value;
                    if (projectId) {
                        fetch(`/projekt/getNextPropertyId/${projectId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            codeInput.value = data.code ?? "";
                        })
                        .catch(error => {
                            console.error("Hiba történt:", error);
                            codeInput.value = "";
                        });
                    } else {
                        codeInput.value = "";
                    }
                });
            }
        });
    </script>',
            'tab' => 'Base',
        ]);

        CRUD::addField([
            'label' => 'Település',
            'type' => 'select',
            'name' => 'settlement_id',
            'entity' => 'settlement',
            'attribute' => 'fullName',
            'model' => Settlement::class,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Településrész',
            'type' => 'select_grouped',
            'name' => 'settlement_part_id',
            'entity' => 'settlementPart',
            'attribute' => 'name',
            'model' => SettlementPart::class,
            'group_by' => 'settlement',
            'group_by_attribute' => 'fullName',
            'group_by_relationship_back' => 'parts',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Ingatlantípus',
            'type' => 'select',
            'name' => 'property_type_id',
            'entity' => 'propertyType',
            'attribute' => 'name',
            'model' => PropertyType::class,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Ingatlan altípus',
            'type' => 'select_grouped',
            'name' => 'property_subtype_id',
            'entity' => 'propertySubtype',
            'attribute' => 'name',
            'model' => PropertySubtype::class,
            'group_by' => 'propertyType',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'subtypes',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Címkék',
            'type' => 'select_multiple',
            'name' => 'labels',
            'entity' => 'labels',
            'model' => Label::class,
            'attribute' => 'name',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-12'],
        ]);

        CRUD::field('images')
            ->type('upload_multiple')
            ->tab('Base')
            ->upload('false')
            ->withFiles([
                'disk' => 'public',
                'path' => 'uploads',
            ])
            ->attributes(['id' => 'input_images'])
            ->wrapperAttributes(['class' => 'col-12']);

        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12']);

        CRUD::field('hide_existing_files_css')
            ->type('custom_html')
            ->value('<style>.well.well-sm.existing-file.mb-2 { display: none !important; }</style>')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12']);

        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor form-control'])
            ->label('Részletes leírás')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);

        CRUD::field('inner_comments')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor form-control'])
            ->label('Belső komment')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);

        // Térkép mezők
        CRUD::addField([
            'name' => 'address',
            'label' => 'Teljes cím (térkép)',
            'type' => 'text',
            'tab' => 'Base',
            'hint' => 'Adja meg a teljes címet, amelyet a térkép megjelenítéshez használunk',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'name' => 'latitude',
            'label' => 'Szélesség (Latitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 47.4979',
                'class' => 'form-control',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
            'wrapperAttributes' => ['class' => 'col-12 col-md-3 col-lg-2'],
        ]);

        CRUD::addField([
            'name' => 'longitude',
            'label' => 'Hosszúság (Longitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 19.0402',
                'class' => 'form-control',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
            'wrapperAttributes' => ['class' => 'col-12 col-md-3 col-lg-2'],
        ]);

        // OpenStreetMap + Leaflet térkép
        CRUD::addField([
            'name' => 'leaflet_map_widget',
            'type' => 'custom_html',
            'value' => $this->getLeafletMapWidget(),
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12'],
        ]);

        // Documents tab
        CRUD::addField([
            'name' => 'documents',
            'type' => 'custom_html',
            'value' => $this->getDocumentsWidget(),
            'tab' => 'Dokumentumok',
            'wrapperAttributes' => ['class' => 'col-12'],
        ]);

        foreach (PropertyAttribute::all() as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'checkbox',
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                        'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
                    ]);
                    break;
                case 'radio':
                    $values = json_decode($attribute->values, false);
                    $values = array_combine($values, $values);

                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'radio',
                        'options' => $values,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                        'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
                    ]);
                    break;

                case 'number':
                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'number',
                        'prefix' => $attribute->prefix,
                        'suffix' => $attribute->suffix,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                        'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
                    ]);
                    break;
                case 'select':
                    $values = (array) json_decode($attribute->values, true);
                    if (isset($values[0]['id'])) {
                        $values = array_combine(array_column($values, 'id'), array_column($values, 'label'));
                    } else {
                        $values = array_combine($values, $values);
                    }

                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'select_from_array',
                        'options' => $values,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                        'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
                    ]);
                    break;
                case 'select_multiple':
                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'select_from_array',
                        'name' => 'properties['.$attribute->id.']',
                        'options' => (array) json_decode($attribute->values),
                        'tab' => $attribute->category->name,
                        'allows_multiple' => true,
                        'wrapperAttributes' => ['class' => 'col-12 col-md-12'],
                    ]);
                    break;
                default:
                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'text',
                        'prefix' => $attribute->prefix,
                        'suffix' => $attribute->suffix,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                        'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
                    ]);
                    break;
            }
        }
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(PropertyRequest::class);
        CRUD::addField([
            'name' => 'is_active',
            'label' => 'Aktív',
            'type' => 'checkbox',
            'default' => true,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);
        CRUD::addField([
            'name' => 'featured',
            'label' => 'Kiemelt',
            'type' => 'checkbox',
            'default' => false,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);
        CRUD::field('title')
            ->type('text')
            ->label('Cím')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);
        CRUD::field('price')
            ->type('number')
            ->label('Irányár')
            ->suffix('Ft')
            ->tab('Base')
            ->attributes([
                'step' => '1',
                'min' => '0',
            ])
            ->wrapperAttributes(['class' => 'col-12 col-md-6 col-lg-4']);

        CRUD::addField([
            'name' => 'ad_type',
            'label' => 'Típus',
            'type' => 'radio',
            'options' => [
                'sell' => 'Eladó',
                'rent' => 'Kiadó',
            ],
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);
        CRUD::addField([
            'label' => 'Ingatlan azonosító',
            'type' => 'text',
            'name' => 'property_code',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'name' => 'custom_script',
            'type' => 'custom_html',
            'value' => '<script>
        document.addEventListener("DOMContentLoaded", function () {
            const projectSelect = document.querySelector("[name=project_id]");
            const codeInput = document.querySelector("[name=property_code]");

            if (projectSelect) {
                projectSelect.addEventListener("change", function () {
                    const projectId = this.value;
                    if (projectId) {
                        fetch(`/projekt/getNextPropertyId/${projectId}`)
                        .then(response => response.json())
                        .then(data => {
                            console.log(data);
                            codeInput.value = data.code ?? "";
                        })
                        .catch(error => {
                            console.error("Hiba történt:", error);
                            codeInput.value = "";
                        });
                    } else {
                        codeInput.value = "";
                    }
                });
            }
        });
    </script>',
            'tab' => 'Base',
        ]);

        CRUD::addField([
            'label' => 'Referens',
            'type' => 'select',
            'name' => 'user_id',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
            'allows_null' => true,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Megbízó',
            'type' => 'select',
            'name' => 'client_id',
            'entity' => 'client',
            'attribute' => 'name',
            'model' => \App\Models\Client::class,
            'allows_null' => true,
            'default' => null,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Projekt',
            'type' => 'select',
            'name' => 'project_id',
            'entity' => 'project',
            'attribute' => 'name',
            'model' => Project::class,
            'allows_null' => true,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Település',
            'type' => 'select',
            'name' => 'settlement_id',
            'entity' => 'settlement',
            'attribute' => 'fullName',
            'model' => Settlement::class,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Településrész',
            'type' => 'select_grouped',
            'name' => 'settlement_part_id',
            'entity' => 'settlementPart',
            'attribute' => 'name',
            'model' => SettlementPart::class,
            'group_by' => 'settlement',
            'group_by_attribute' => 'fullName',
            'group_by_relationship_back' => 'parts',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Ingatlantípus',
            'type' => 'select',
            'name' => 'property_type_id',
            'entity' => 'propertyType',
            'attribute' => 'name',
            'model' => PropertyType::class,
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Ingatlan altípus',
            'type' => 'select_grouped',
            'name' => 'property_subtype_id',
            'entity' => 'propertySubtype',
            'attribute' => 'name',
            'model' => PropertySubtype::class,
            'group_by' => 'propertyType',
            'group_by_attribute' => 'name',
            'group_by_relationship_back' => 'subtypes',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'label' => 'Címkék',
            'type' => 'select_multiple',
            'name' => 'labels',
            'entity' => 'labels',
            'model' => Label::class,
            'attribute' => 'name',
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12 col-md-12'],
        ]);

        CRUD::field('images')
            ->type('upload_multiple')
            ->tab('Base')
            ->prefix('storage/')
            ->upload('false')
            ->withFiles([
                'disk' => 'public',
                'path' => 'uploads',
                'fileNamer' => \Backpack\CRUD\app\Library\Uploaders\Support\FileNameGenerator::class,
            ])
            ->attributes(['id' => 'input_images'])
            ->wrapperAttributes(['class' => 'col-12']);

        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12']);

        CRUD::field('hide_existing_files_css')
            ->type('custom_html')
            ->value('<style>.well.well-sm.existing-file.mb-2 { display: none !important; }</style>')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12']);

        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor form-control'])
            ->label('Részletes leírás')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);

        CRUD::field('inner_comments')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor form-control'])
            ->label('Belső komment')
            ->tab('Base')
            ->wrapperAttributes(['class' => 'col-12 col-md-12']);

        // Térkép mezők
        CRUD::addField([
            'name' => 'address',
            'label' => 'Teljes cím (térkép)',
            'type' => 'text',
            'tab' => 'Base',
            'hint' => 'Adja meg a teljes címet, amelyet a térkép megjelenítéshez használunk',
            'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
        ]);

        CRUD::addField([
            'name' => 'latitude',
            'label' => 'Szélesség (Latitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 47.4979',
                'class' => 'form-control',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
            'wrapperAttributes' => ['class' => 'col-12 col-md-3 col-lg-2'],
        ]);

        CRUD::addField([
            'name' => 'longitude',
            'label' => 'Hosszúság (Longitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 19.0402',
                'class' => 'form-control',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
            'wrapperAttributes' => ['class' => 'col-12 col-md-3 col-lg-2'],
        ]);

        // OpenStreetMap + Leaflet térkép
        CRUD::addField([
            'name' => 'leaflet_map_widget',
            'type' => 'custom_html',
            'value' => $this->getLeafletMapWidget(),
            'tab' => 'Base',
            'wrapperAttributes' => ['class' => 'col-12'],
        ]);

        // Documents tab
        CRUD::addField([
            'name' => 'documents',
            'type' => 'custom_html',
            'value' => $this->getDocumentsWidget(),
            'tab' => 'Dokumentumok',
            'wrapperAttributes' => ['class' => 'col-12'],
        ]);

        $propertyId = Route::current()->parameter('id');
        $property = \App\Models\Property::with('attributes')->findOrFail($propertyId);

        foreach ($property->attributes as $attribute) {
            $field = [
                'name' => 'properties['.$attribute->id.']',
                'label' => $attribute->label,
                'type' => $this->mapAttributeType($attribute->type),
                'value' => $attribute->pivot->value,
                'tab' => $attribute->category->name,
                'prefix' => $attribute->prefix,
                'suffix' => $attribute->suffix,
                'wrapperAttributes' => ['class' => 'col-12 col-md-6 col-lg-4'],
            ];

            // ha select, akkor a JSON értékek alapján adjunk meg opciókat
            if (in_array($attribute->type, ['select', 'radio'])) {
                $values = (array) json_decode($attribute->values, true);
                foreach ($values as $k => $value) {
                    $values[(string) $k] = (string) $value;
                }

                $field['options'] = $values;
            }
            if ($attribute->type == 'select_multiple') {
                $values = (array) json_decode($attribute->values, true);
                foreach ($values as $k => $value) {
                    $values[(int) $k] = (string) $value;
                }

                $field['allows_multiple'] = true;
                $field['attributes'] = ['multiple' => 'multiple'];
                $field['options'] = $values;
                $field['value'] = json_decode($attribute->pivot->value, true);
                $field['wrapperAttributes'] = ['class' => 'col-12 col-md-12'];
            }

            CRUD::addField($field);
        }

    }

    protected function setupPropertyActiveRoutes($segment, $routeName, $controller)
    {
        Route::get('/property-active', [
            'as' => $routeName.'.propertyActive',
            'uses' => $controller.'@listActive',
            'operation' => 'aktiv',
        ]);

        Route::post('/property-active/search', [
            'as' => $routeName.'.propertyActivePost',
            'uses' => $controller.'@listActivePost',
            'operation' => 'aktiv',
        ]);
    }

    public function listActive()
    {
        $this->crud->loadDefaultOperationSettingsFromConfig('backpack.operations.list');

        $this->crud->setRoute('admin/property-active');
        $this->crud->setOperation('aktiv');

        // Get the entries
        $this->data['crud'] = $this->crud;
        $this->data['title'] = 'Aktív ingatlanok';
        $this->data['entries'] = $this->crud->getEntries();

        // Load the list view
        return view('vendor.backpack.crud.list', $this->data);

    }

    public function listActivePost()
    {
        $this->crud->loadDefaultOperationSettingsFromConfig('backpack.operations.list');
        $this->crud->setOperation('aktiv');

        return $this->search();
    }

    private function mapAttributeType($type)
    {
        return match ($type) {
            'text' => 'text',
            'number' => 'number',
            'checkbox' => 'checkbox',
            'select' => 'select_from_array',
            'radio' => 'radio',
            'select_multiple' => 'select_from_array',
            default => 'text',
        };
    }

    public function store()
    {
        try {
            $this->crud->hasAccessOrFail('create');

            // execute the FormRequest authorization and validation, if one is required
            $request = $this->crud->validateRequest();

            // register any Model Events defined on fields
            $this->crud->registerFieldEvents();

            // insert item in the db
            $itemAttributes = $this->crud->getStrippedSaveRequest($request);
            $item = $this->crud->create($itemAttributes);

            UniqueCode::updateCode((int) explode('/', $itemAttributes['property_code'])[0]);

            $properties = $request->get('properties', []);
            foreach ($properties as $attributeId => $attributeValue) {
                $selectedValues = $request->input('properties.'.$attributeId);

                if (is_array($selectedValues)) {
                    $valueToSave = json_encode($selectedValues);
                } else {
                    $valueToSave = $selectedValues;
                }

                $item->attributes()->syncWithoutDetaching([
                    $attributeId => ['value' => $valueToSave],
                ]);
            }

            $this->data['entry'] = $this->crud->entry = $item;

            // show a success message
            \Alert::success(trans('vendor.backpack.crud.insert_success'))->flash();

            // save the redirect choice for next time
            $this->crud->setSaveAction();

            return $this->crud->performSaveAction($item->getKey());
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show proper error messages
            throw $e;
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Property creation error: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => request()->all(),
            ]);

            // Return to form with error message
            \Alert::error('Hiba történt az ingatlan létrehozása során: '.$e->getMessage())->flash();

            return redirect()->back()->withInput();
        }
    }

    public function update()
    {
        try {
            $this->crud->hasAccessOrFail('update');

            $request = $this->crud->validateRequest();

            // frissítés maga
            $itemAttributes = $this->crud->getStrippedSaveRequest($request);
            $item = $this->crud->update(
                Route::current()->parameter($this->crud->getModel()->getRouteKeyName()),
                $itemAttributes
            );

            UniqueCode::updateCode((int) explode('/', $itemAttributes['property_code'])[0]);

            // attributumok frissítése
            $propertyAttributes = $request->get('properties', []);

            foreach ($propertyAttributes as $attributeId => $value) {
                // Ellenőrizzük, hogy már létezik-e a pivotban
                $item->attributes()->syncWithoutDetaching([
                    $attributeId => ['value' => $value],
                ]);
            }

            $this->data['entry'] = $this->crud->entry = $item;

            \Alert::success(trans('vendor.backpack.crud.update_success'))->flash();

            $this->crud->setSaveAction();

            return $this->crud->performSaveAction($item->getKey());
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Re-throw validation exceptions to show proper error messages
            throw $e;
        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Property update error: '.$e->getMessage(), [
                'exception' => $e,
                'request_data' => request()->all(),
            ]);

            // Return to form with error message
            \Alert::error('Hiba történt az ingatlan frissítése során: '.$e->getMessage())->flash();

            return redirect()->back()->withInput();
        }
    }

    public function findPropertyOrProject(string $unique_id)
    {
        $property = Property::where('property_code', $unique_id)->first();

        if (! $property) {
            $project = Project::where('project_code', $unique_id)->first();

            return redirect('/admin/project/'.$project->id.'/edit');

        }

        return redirect('/admin/property/'.$property->id.'/show');
    }

    protected function setupPropertyInactiveRoutes($segment, $routeName, $controller)
    {

        Route::get('/property-inactive', [
            'as' => $routeName.'.propertyInactive',
            'uses' => $controller.'@listInactive',
            'operation' => 'fasz',
        ]);

        Route::post('/property-inactive/search', [
            'as' => $routeName.'.propertyInactivePost',
            'uses' => $controller.'@listInactivePost',
            'operation' => 'fasz',
        ]);

        Route::get('/property-sajat', [
            'as' => $routeName.'.propertySajat',
            'uses' => $controller.'@listSajat',
            'operation' => 'faszt',
        ]);

        Route::post('/property-sajat/search', [
            'as' => $routeName.'.propertySajatPost',
            'uses' => $controller.'@listSajatPost',
            'operation' => 'faszt',
        ]);
    }

    public function listInactive()
    {
        $this->crud->loadDefaultOperationSettingsFromConfig('backpack.operations.list');

        $this->crud->setRoute('admin/property-inactive');
        $this->crud->setOperation('fasz');

        // Get the entries
        $this->data['crud'] = $this->crud;
        $this->data['title'] = 'Inaktív ingatlanok';
        $this->data['entries'] = $this->crud->getEntries();

        // Load the list view
        return view('vendor.backpack.crud.list', $this->data);
    }

    public function listInactivePost()
    {
        $this->crud->loadDefaultOperationSettingsFromConfig('backpack.operations.list');
        $this->crud->setOperation('fasz');

        return $this->search();
    }

    public function listSajat()
    {
        $this->crud->loadDefaultOperationSettingsFromConfig('backpack.operations.list');

        $this->crud->setRoute('admin/property-sajat');
        $this->crud->setOperation('faszt');

        // Get the entries
        $this->data['crud'] = $this->crud;
        $this->data['title'] = 'Saját ingatlanok';
        $this->data['entries'] = $this->crud->getEntries();

        // Load the list view
        return view('vendor.backpack.crud.list', $this->data);
    }

    public function listSajatPost()
    {
        $this->crud->loadDefaultOperationSettingsFromConfig('backpack.operations.list');
        $this->crud->setOperation('faszt');

        return $this->search();
    }

    private function getLeafletMapWidget(): string
    {
        return '
            <!-- Leaflet CSS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

            <div id="leaflet-map-container" class="mt-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light">
                        <h6 class="mb-0 text-primary"><i class="fas fa-map-marked-alt me-2"></i>Térkép</h6>
                    </div>
                    <div class="card-body p-0">
                        <div id="map" style="height: 400px; width: 100%;"></div>
                    </div>
                    <div class="card-footer bg-light">
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" id="get-current-location" class="btn btn-info btn-sm">
                                <i class="fas fa-location-arrow me-1"></i>Jelenlegi helyzet
                            </button>
                            <button type="button" id="search-address" class="btn btn-primary btn-sm">
                                <i class="fas fa-search me-1"></i>Cím keresése
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Leaflet JavaScript -->
            <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

            <script>
                let map;
                let marker;

                function initMap() {
                    // Meglévő koordináták betöltése vagy alapértelmezett (Budapest)
                    const lat = parseFloat(document.querySelector("[name=latitude]").value) || 47.4979;
                    const lng = parseFloat(document.querySelector("[name=longitude]").value) || 19.0402;
                    const defaultLocation = [lat, lng];

                    // Térkép inicializálása
                    map = L.map("map").setView(defaultLocation, 15);

                    // OpenStreetMap tile layer hozzáadása
                    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                        attribution: "© OpenStreetMap contributors",
                        maxZoom: 19
                    }).addTo(map);

                    // Marker létrehozása
                    marker = L.marker(defaultLocation, { draggable: true }).addTo(map);

                    // Marker mozgatás esemény
                    marker.on("dragend", function(e) {
                        const position = e.target.getLatLng();
                        document.querySelector("[name=latitude]").value = position.lat.toFixed(8);
                        document.querySelector("[name=longitude]").value = position.lng.toFixed(8);

                        // Geocoding fordított irányba (Nominatim API)
                        fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.lat}&lon=${position.lng}`)
                            .then(response => response.json())
                            .then(data => {
                                if (data.display_name) {
                                    document.querySelector("[name=address]").value = data.display_name;
                                }
                            })
                            .catch(error => console.log("Geocoding error:", error));
                    });

                    // Cím keresés gomb
                    document.getElementById("search-address").addEventListener("click", function() {
                        const address = document.querySelector("[name=address]").value;
                        if (address) {
                            // Geocoding (Nominatim API)
                            fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`)
                                .then(response => response.json())
                                .then(data => {
                                    if (data.length > 0) {
                                        const location = [parseFloat(data[0].lat), parseFloat(data[0].lon)];
                                        map.setView(location, 15);
                                        marker.setLatLng(location);
                                        document.querySelector("[name=latitude]").value = data[0].lat;
                                        document.querySelector("[name=longitude]").value = data[0].lon;
                                    } else {
                                        alert("A cím nem található");
                                    }
                                })
                                .catch(error => {
                                    console.log("Geocoding error:", error);
                                    alert("Hiba történt a cím keresése során");
                                });
                        }
                    });

                    // Jelenlegi helyzet gomb
                    document.getElementById("get-current-location").addEventListener("click", function() {
                        if (navigator.geolocation) {
                            navigator.geolocation.getCurrentPosition((position) => {
                                const pos = [position.coords.latitude, position.coords.longitude];
                                map.setView(pos, 15);
                                marker.setLatLng(pos);
                                document.querySelector("[name=latitude]").value = position.coords.latitude.toFixed(8);
                                document.querySelector("[name=longitude]").value = position.coords.longitude.toFixed(8);

                                // Geocoding fordított irányba
                                fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${position.coords.latitude}&lon=${position.coords.longitude}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.display_name) {
                                            document.querySelector("[name=address]").value = data.display_name;
                                        }
                                    })
                                    .catch(error => console.log("Geocoding error:", error));
                            });
                        } else {
                            alert("A böngésző nem támogatja a geolokációt.");
                        }
                    });
                }

                // DOM betöltés után inicializálás
                document.addEventListener("DOMContentLoaded", function() {
                    initMap();
                });
            </script>
        ';
    }

    public function toggleActive($id)
    {
        $property = Property::findOrFail($id);
        $newState = $property->toggleActive();

        return response()->json([
            'success' => true,
            'is_active' => $newState,
            'message' => $newState ? 'Ingatlan aktiválva' : 'Ingatlan deaktiválva',
        ]);
    }

    public function showMatchingSearches($id)
    {
        $property = Property::findOrFail($id);
        $propertyService = new \App\Services\PropertyService;
        $matches = $propertyService->findMatchingCustomerSearches($property);

        return view('vendor.backpack.property.matching-searches', [
            'property' => $property,
            'matches' => $matches,
        ]);
    }

    public function sendToMatchingSearch(Request $request, $propertyId, $searchId)
    {
        $property = Property::findOrFail($propertyId);
        $search = \App\Models\CustomerSearch::findOrFail($searchId);

        // Send email
        try {
            \Mail::to($search->customer->email)->send(new \App\Mail\PropertiesForCustomer($search->customer, collect([$property])));

            // Save offer record
            \App\Models\CustomerOffer::create([
                'customer_id' => $search->customer_id,
                'customer_search_id' => $searchId,
                'property_ids' => [$propertyId],
                'email_subject' => 'Ingatlan ajánlat - '.$search->customer->name_0,
                'email_content' => 'Kedves '.$search->customer->name_0.'! Küldjük Önnek a keresési paramétereinek megfelelő ingatlan ajánlatot.',
                'sent_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Ajánlat sikeresen elküldve']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hiba történt az email küldése során: '.$e->getMessage()], 500);
        }
    }

    private function getDocumentsWidget(): string
    {
        $propertyId = request()->route('id') ?? 'new';
        $documents = $propertyId !== 'new' ? \App\Models\PropertyDocument::where('property_id', $propertyId)->get() : collect();

        return '
        <div id="documents-widget">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0 text-primary"><i class="fas fa-file-alt me-2"></i>Dokumentumok kezelése</h5>
                </div>
                <div class="card-body p-4">
                    <!-- Upload Form -->
                    <div class="mb-4">
                        <div id="document-upload-form">
                            <div class="row g-3">
                                <div class="col-12 col-md-6 col-lg-3">
                                    <label for="document-name" class="form-label fw-semibold">Dokumentum neve <small class="text-muted">(opcionális)</small></label>
                                    <input type="text" class="form-control form-control-sm" id="document-name" name="name" placeholder="Ha üres, a fájl neve lesz használva">
                                </div>
                                <div class="col-12 col-md-6 col-lg-3">
                                    <label for="document-category" class="form-label fw-semibold">Kategória <small class="text-muted">(opcionális)</small></label>
                                    <select class="form-select form-select-sm" id="document-category" name="category">
                                        <option value="">Válassz kategóriát</option>
                                        <option value="contract">Szerződés</option>
                                        <option value="order">Megrendelő</option>
                                        <option value="purchase">Vételi</option>
                                        <option value="inspection">Szemle</option>
                                        <option value="other">Egyéb</option>
                                    </select>
                                </div>
                                <div class="col-12 col-md-8 col-lg-4">
                                    <label for="document-file" class="form-label fw-semibold">Fájl <small class="text-danger">*</small></label>
                                    <input type="file" class="form-control form-control-sm" id="document-file" name="file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-12 col-md-4 col-lg-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="upload-document-btn" class="btn btn-primary btn-sm d-block w-100">
                                        <i class="fas fa-upload me-1"></i>Feltöltés
                                    </button>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-12">
                                    <label for="document-description" class="form-label fw-semibold">Leírás (opcionális)</label>
                                    <textarea class="form-control form-control-sm" id="document-description" name="description" rows="2" placeholder="Opcionális leírás a dokumentumhoz..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Documents List -->
                    <div id="documents-list">
                        '.$this->renderDocumentsList($documents).'
                    </div>
                </div>
            </div>
        </div>
        
        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const uploadBtn = document.getElementById("upload-document-btn");
            const documentsList = document.getElementById("documents-list");
            
            if (uploadBtn) {
                uploadBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();
                    
                    // Get form values
                    const name = document.getElementById("document-name").value;
                    const category = document.getElementById("document-category").value;
                    const file = document.getElementById("document-file").files[0];
                    
                    // If no file is selected, show message and return
                    if (!file) {
                        alert("Kérjük, válasszon ki egy fájlt a feltöltéshez!");
                        return;
                    }
                    
                    // If file is selected but no name or category, use defaults
                    const finalName = name || file.name;
                    const finalCategory = category || "other";
                    
                    // Disable button during upload
                    uploadBtn.disabled = true;
                    uploadBtn.textContent = "Feltöltés...";
                    
                    // Create FormData manually
                    const formData = new FormData();
                    formData.append("name", finalName);
                    formData.append("category", finalCategory);
                    formData.append("file", file);
                    formData.append("description", document.getElementById("document-description").value);
                    formData.append("_token", document.querySelector(\'meta[name="csrf-token"]\').getAttribute("content"));
                    
                    fetch("/admin/property/'.$propertyId.'/upload-document", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Dokumentum sikeresen feltöltve!");
                            // Reload documents list
                            location.reload();
                        } else {
                            alert("Hiba: " + data.message);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Hiba történt a feltöltés során");
                    })
                    .finally(() => {
                        // Re-enable button
                        uploadBtn.disabled = false;
                        uploadBtn.textContent = "Feltöltés";
                    });
                });
            }
            
            // Debug: Check if property form is working
            console.log("Document widget loaded, property form should work normally");
        });
        
        function deleteDocument(documentId) {
            if (confirm("Biztosan törölni szeretnéd ezt a dokumentumot?")) {
                fetch("/admin/property/'.$propertyId.'/document/" + documentId, {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').getAttribute("content"),
                        "Content-Type": "application/json"
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        location.reload();
                    } else {
                        alert("Hiba: " + data.message);
                    }
                })
                .catch(error => {
                    console.error("Error:", error);
                    alert("Hiba történt a törlés során");
                });
            }
        }
        </script>';
    }

    private function renderDocumentsList($documents): string
    {
        if ($documents->isEmpty()) {
            return '<div class="text-center p-4">
                <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
                <p class="text-muted mb-0">Még nincsenek feltöltött dokumentumok.</p>
            </div>';
        }

        $html = '<div class="table-responsive"><table class="table table-hover align-middle mb-0">';
        $html .= '<thead class="table-light"><tr>
            <th><i class="fas fa-file me-1"></i>Név</th>
            <th><i class="fas fa-tag me-1"></i>Kategória</th>
            <th><i class="fas fa-paperclip me-1"></i>Fájl</th>
            <th><i class="fas fa-weight me-1"></i>Méret</th>
            <th><i class="fas fa-calendar me-1"></i>Feltöltve</th>
            <th><i class="fas fa-cogs me-1"></i>Műveletek</th>
        </tr></thead><tbody>';

        foreach ($documents as $document) {
            $html .= '<tr>';
            $html .= '<td>
                <div class="d-flex align-items-center">
                    <i class="fas fa-file-'.($document->file_type === 'application/pdf' ? 'pdf text-danger' : 'image text-primary').' me-2"></i>
                    <span class="fw-semibold">'.htmlspecialchars($document->name).'</span>
                </div>
            </td>';
            $html .= '<td><span class="badge bg-secondary rounded-pill px-3 py-2">'.htmlspecialchars($document->category_name).'</span></td>';
            $html .= '<td>
                <a href="'.$document->file_url.'" target="_blank" class="text-decoration-none fw-medium">
                    <i class="fas fa-external-link-alt me-1"></i>'.htmlspecialchars($document->original_name).'
                </a>
            </td>';
            $html .= '<td><small class="text-muted">'.htmlspecialchars($document->file_size_human).'</small></td>';
            $html .= '<td><small class="text-muted">'.htmlspecialchars($document->created_at->format('Y-m-d H:i')).'</small></td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-outline-danger" onclick="deleteDocument('.$document->id.')">
                <i class="fas fa-trash me-1"></i>Törlés
            </button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Upload a document for a property
     */
    public function uploadDocument(Request $request, $propertyId)
    {
        try {
            \Log::info('Document upload attempt', [
                'property_id' => $propertyId,
                'request_data' => $request->all(),
                'files' => $request->files->all(),
            ]);

            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $property = Property::findOrFail($propertyId);
            $file = $request->file('file');

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('property-documents/'.$propertyId, $filename, 'public');

            \Log::info('File stored', ['path' => $path]);

            // Create document record with defaults for optional fields
            $document = PropertyDocument::create([
                'property_id' => $propertyId,
                'name' => $request->name ?: $file->getClientOriginalName(),
                'category' => $request->category ?: 'other',
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->description,
            ]);

            \Log::info('Document created', ['document_id' => $document->id]);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve',
                'document' => $document,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error', ['errors' => $e->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Validációs hiba',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Document upload error: '.$e->getMessage(), [
                'exception' => $e,
                'property_id' => $propertyId,
                'request_data' => $request->all(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a feltöltés során: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a document
     */
    public function deleteDocument(Request $request, $propertyId, $documentId)
    {
        try {
            $document = PropertyDocument::where('property_id', $propertyId)
                ->where('id', $documentId)
                ->firstOrFail();

            $document->delete(); // This will also delete the file due to the model's boot method

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve',
            ]);

        } catch (\Exception $e) {
            \Log::error('Document deletion error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a törlés során: '.$e->getMessage(),
            ], 500);
        }
    }
}
