<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyRequest;
use App\Models\Label;
use App\Models\PropertyAttribute;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\SettlementPart;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Support\Facades\Route;

/**
 * Class PropertyCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class PropertyCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;


    public function setup()
    {
        CRUD::setModel(\App\Models\Property::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/property');
        CRUD::setEntityNameStrings('property', 'properties');
    }

    protected function setupListOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        $this->crud->addColumns([
            [
                'name'  => 'featured',
                'label' => 'Kiemelt',
                'type'  => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text'
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'number'
            ],
            [
                'name' => 'ad_type',
                'label' => 'Típus',
                'type' => 'radio',
                // optional, specify the enum options with custom display values
                'options' => [
                    'sell' => 'Eladó',
                    'rent' => 'Kiadó',
                ],
            ],
            [   // select_grouped
                'label' => 'Település',
                'type' => 'select',
                'name' => 'settlement_id',
                'entity' => 'settlement',
                'attribute' => 'fullName',
                'model' => Settlement::class,
                'tab' => 'Base',
            ],
            [   // select_grouped
                'label' => 'Településrész',
                'type' => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
                'name' => 'settlement_part_id',
                'entity' => 'settlementPart',
                'attribute' => 'name',
                'model' => SettlementPart::class,
                'group_by' => 'settlement', // the relationship to entity you want to use for grouping
                'group_by_attribute' => 'fullName', // the attribute on related model, that you want shown
                'group_by_relationship_back' => 'parts', // relationship from related model back to this model
                'tab' => 'Base',
            ],
            [   // select_grouped
                'label' => 'Ingatlantípus',
                'type' => 'select',
                'name' => 'property_type_id',
                'entity' => 'propertyType',
                'attribute' => 'name',
                'model' => PropertyType::class,
                'tab' => 'Base',
            ],
            [   // select_grouped
                'label' => 'Ingatlan altípus',
                'type' => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
                'name' => 'property_subtype_id',
                'entity' => 'propertySubtype',
                'attribute' => 'name',
                'model' => PropertySubtype::class,
                'group_by' => 'propertyType', // the relationship to entity you want to use for grouping
                'group_by_attribute' => 'name', // the attribute on related model, that you want shown
                'group_by_relationship_back' => 'subtypes', // relationship from related model back to this model
                'tab' => 'Base',
            ],
            [
                'name' => 'description',
                'label' => 'Leírás',
                'type' => 'text'
            ],
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        CRUD::addField([
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ]
        );
        CRUD::field('title')->type('text')->label('Cím')->tab('Base');
        CRUD::field('price')->type('number')->label('Irányár')->suffix('M Ft')->tab('Base');
        CRUD::addField([
            'name' => 'ad_type',
            'label' => 'Típus',
            'type' => 'radio',
            // optional, specify the enum options with custom display values
            'options' => [
                'sell' => 'Eladó',
                'rent' => 'Kiadó',
            ],
            'tab' => 'Base'
        ]);
        CRUD::addField([   // select_grouped
            'label' => 'Település',
            'type' => 'select',
            'name' => 'settlement_id',
            'entity' => 'settlement',
            'attribute' => 'fullName',
            'model' => Settlement::class,
            'tab' => 'Base',
        ]);

        CRUD::addField([   // select_grouped
            'label' => 'Településrész',
            'type' => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
            'name' => 'settlement_part_id',
            'entity' => 'settlementPart',
            'attribute' => 'name',
            'model' => SettlementPart::class,
            'group_by' => 'settlement', // the relationship to entity you want to use for grouping
            'group_by_attribute' => 'fullName', // the attribute on related model, that you want shown
            'group_by_relationship_back' => 'parts', // relationship from related model back to this model
            'tab' => 'Base',
        ]);
        CRUD::addField([   // select_grouped
            'label' => 'Ingatlantípus',
            'type' => 'select',
            'name' => 'property_type_id',
            'entity' => 'propertyType',
            'attribute' => 'name',
            'model' => PropertyType::class,
            'tab' => 'Base',
        ]);

        CRUD::addField([   // select_grouped
            'label' => 'Ingatlan altípus',
            'type' => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
            'name' => 'property_subtype_id',
            'entity' => 'propertySubtype',
            'attribute' => 'name',
            'model' => PropertySubtype::class,
            'group_by' => 'propertyType', // the relationship to entity you want to use for grouping
            'group_by_attribute' => 'name', // the attribute on related model, that you want shown
            'group_by_relationship_back' => 'subtypes', // relationship from related model back to this model
            'tab' => 'Base',
        ]);

        CRUD::addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => "labels",
            'type' => 'select_multiple',
            'name' => 'labels', // the method that defines the relationship in your Model

            // optional
            'entity' => 'labels', // the method that defines the relationship in your Model
            'model' => Label::class, // foreign key model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'tab' => 'Base',
        ]);

        CRUD::field('images')
            ->type('upload_multiple')
            ->tab('Base')
            ->withFiles(
                [
                    'disk' => 'public', // the disk where file will be stored
                    'path' => 'uploads', // the path inside the disk where file will be stored
                ]
            );

        CRUD::field('description')->type('summernote')->label('Részletes leírás')->tab('Base');


        foreach (PropertyAttribute::all() as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'checkbox',
                        'name' => 'properties[' . $attribute->id . ']',
                        'tab' => $attribute->category->name
                    ]);
                    break;
                case 'radio':
                    $values = json_decode($attribute->values, false);
                    $values = array_combine($values, $values);

                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'radio',
                        'options' => $values,
                        'name' => 'properties[' . $attribute->id . ']',
                        'tab' => $attribute->category->name
                    ]);
                    break;

                case 'number':
                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'number',
                        'prefix' => $attribute->prefix,
                        'suffix' => $attribute->suffix,
                        'name' => 'properties[' . $attribute->id . ']',
                        'tab' => $attribute->category->name,
                    ]);
                    break;
                case 'select':
                    $values = json_decode($attribute->values, false);
                    $values = array_combine($values, $values);

                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'select_from_array',
                        'options' => $values,
                        'name' => 'properties[' . $attribute->id . ']',
                        'tab' => $attribute->category->name
                    ]);
                    break;
                default:
                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'text',
                        'prefix' => $attribute->prefix,
                        'suffix' => $attribute->suffix,
                        'name' => 'properties[' . $attribute->id . ']',
                        'tab' => $attribute->category->name
                    ]);

            }

        }
    }

    protected function setupUpdateOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        CRUD::addField([
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ]
        );
        CRUD::field('title')->type('text')->label('Cím')->tab('Base');
        CRUD::field('price')->type('number')->label('Irányár')->suffix('M Ft')->tab('Base');
        CRUD::addField([
            'name' => 'ad_type',
            'label' => 'Típus',
            'type' => 'radio',
            // optional, specify the enum options with custom display values
            'options' => [
                'sell' => 'Eladó',
                'rent' => 'Kiadó',
            ],
            'tab' => 'Base'
        ]);
        CRUD::addField([   // select_grouped
            'label' => 'Település',
            'type' => 'select',
            'name' => 'settlement_id',
            'entity' => 'settlement',
            'attribute' => 'fullName',
            'model' => Settlement::class,
            'tab' => 'Base',
        ]);

        CRUD::addField([   // select_grouped
            'label' => 'Településrész',
            'type' => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
            'name' => 'settlement_part_id',
            'entity' => 'settlementPart',
            'attribute' => 'name',
            'model' => SettlementPart::class,
            'group_by' => 'settlement', // the relationship to entity you want to use for grouping
            'group_by_attribute' => 'fullName', // the attribute on related model, that you want shown
            'group_by_relationship_back' => 'parts', // relationship from related model back to this model
            'tab' => 'Base',
        ]);
        CRUD::addField([   // select_grouped
            'label' => 'Ingatlantípus',
            'type' => 'select',
            'name' => 'property_type_id',
            'entity' => 'propertyType',
            'attribute' => 'name',
            'model' => PropertyType::class,
            'tab' => 'Base',
        ]);

        CRUD::addField([   // select_grouped
            'label' => 'Ingatlan altípus',
            'type' => 'select_grouped', //https://github.com/Laravel-Backpack/CRUD/issues/502
            'name' => 'property_subtype_id',
            'entity' => 'propertySubtype',
            'attribute' => 'name',
            'model' => PropertySubtype::class,
            'group_by' => 'propertyType', // the relationship to entity you want to use for grouping
            'group_by_attribute' => 'name', // the attribute on related model, that you want shown
            'group_by_relationship_back' => 'subtypes', // relationship from related model back to this model
            'tab' => 'Base',
        ]);

        CRUD::addField([   // SelectMultiple = n-n relationship (with pivot table)
            'label' => "labels",
            'type' => 'select_multiple',
            'name' => 'labels', // the method that defines the relationship in your Model

            // optional
            'entity' => 'labels', // the method that defines the relationship in your Model
            'model' => Label::class, // foreign key model
            'attribute' => 'name', // foreign key attribute that is shown to user
            'tab' => 'Base',
        ]);

        CRUD::field('images')
            ->type('upload_multiple')
            ->tab('Base')
            ->withFiles(
                [
                    'disk' => 'public', // the disk where file will be stored
                    'path' => 'uploads', // the path inside the disk where file will be stored
                ]
            );

        CRUD::field('description')->type('summernote')->label('Részletes leírás')->tab('Base');

        $propertyId = Route::current()->parameter('id');
        $property = \App\Models\Property::with('attributes')->findOrFail($propertyId);

        foreach ($property->attributes as $attribute) {
            $field = [
                'name' => 'properties[' . $attribute->id . ']', // pl. attribute_5
                'label' => $attribute->name,
                'type' => $this->mapAttributeType($attribute->type),
                'value' => $attribute->pivot->value,
                'tab' => $attribute->category->name,
                'prefix' => $attribute->prefix,
                'suffix' => $attribute->suffix
            ];

            // ha select, akkor a JSON értékek alapján adjunk meg opciókat
            if (in_array($attribute->type, ['select', 'radio'])) {
                $values = json_decode($attribute->values, false);
                $values = array_combine($values, $values);

                $field['options'] = $values;
            }

            CRUD::addField($field);
        }

    }

    private function mapAttributeType($type)
    {
        return match ($type) {
            'text' => 'text',
            'number' => 'number',
            'checkbox' => 'checkbox',
            'select' => 'select_from_array',
            'radio' => 'radio',
            default => 'text',
        };
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

        foreach ($request->get('properties') as $attributeId => $attributeValue) {
            $item->attributes()->attach($attributeId, ['value' => $attributeValue]);
        }

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

        // attributumok frissítése
        $propertyAttributes = $request->get('properties', []);

        foreach ($propertyAttributes as $attributeId => $value) {
            // Ellenőrizzük, hogy már létezik-e a pivotban
            $item->attributes()->syncWithoutDetaching([
                $attributeId => ['value' => $value]
            ]);
        }

        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

}
