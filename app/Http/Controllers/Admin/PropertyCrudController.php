<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\PropertyRequest;
use App\Models\Label;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyAttribute;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\SettlementPart;
use App\Models\UniqueCode;
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
                'name'  => 'is_active',
                'label' => 'Aktív',
                'type'  => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ],
            [
                'name'  => 'featured',
                'label' => 'Kiemelt',
                'type'  => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
                'type' => 'text'
            ],
            [
                'name' => 'title',
                'label' => 'Cím',
                'type' => 'text'
            ],
            [
                'name' => 'price',
                'label' => 'Ár',
                'type' => 'model_function',
                'function_name' => 'getFormattedPrice',
                'suffix' => ' M',
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
        ]);
    }

    protected function setupCreateOperation()
    {
        CRUD::setValidation(PropertyRequest::class);

        CRUD::addField([
                'name' => 'is_active',
                'label' => 'aktív',
                'type' => 'checkbox',
                'default' => true,
                'tab' => 'Base'
            ]
        );

        CRUD::addField([
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ]
        );
        CRUD::field('title')->type('text')->label('Cím')->tab('Base');
        CRUD::field('price')->type('number')->label('Irányár')->suffix('M Ft')->tab('Base')->attributes([
            'step' => '0.01', // ez engedélyezi a tizedes számokat
            'min' => '0',     // opcionális
        ]);
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
        CRUD::addField([
            'label' => 'Projekt',
            'type' => 'select',
            'name' => 'project_id',
            'entity' => 'project',
            'attribute' => 'name',
            'model' => Project::class,
            'allows_null' => true, // This option allows no selection by default
            'default' => null,     // Explicitly sets the default value to null (optional)
            'tab' => 'Base',
        ]);
        CRUD::addField([
            'label' => "Ingatlan azonosító",
            'type' => 'text',
            'name' => 'property_code',
            'value' => UniqueCode::getNextCode(),
            'tab' => 'Base',
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
            ->upload('false')
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
            ->tab('Base'); // vagy bármi a tab neve



        CRUD::field('description')
            ->type('textarea')
            ->attributes(['id' => 'ckeditor']) // ID, hogy felismerje
            ->label('Részletes leírás')
            ->tab('Base');


        foreach (PropertyAttribute::all() as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'checkbox',
                        'name' => 'properties[' . $attribute->id . ']',
                        'tab' => $attribute->category->name,
                        'wrapperAttributes' => ['class' => 'col-4'], // wrapper div-hez
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
                'name' => 'is_active',
                'label' => 'aktív',
                'type' => 'checkbox',
                'default' => true,
                'tab' => 'Base'
            ]
        );
        CRUD::addField([
                'name' => 'featured',
                'label' => 'Kiemelt',
                'type' => 'checkbox',
                'default' => false,
                'tab' => 'Base'
            ]
        );
        CRUD::field('title')->type('text')->label('Cím')->tab('Base');
        CRUD::field('price')->type('number')->label('Irányár')->suffix('M Ft')->tab('Base')->attributes([
            'step' => '0.01', // ez engedélyezi a tizedes számokat
            'min' => '0',     // opcionális
        ]);

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
        CRUD::addField([
            'label' => "Ingatlan azonosító",
            'type' => 'text',
            'name' => 'property_code',
            'tab' => 'Base',
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
            'tab' => 'Teszt',
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
            )->attributes([
                'id' => 'input_images', // 💡 ID hozzáadása a JS miatt
            ]);

        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
            ->tab('Base'); // vagy bármi a tab neve

        CRUD::field('description')
            ->type('textarea')
            ->attributes(['id' => 'ckeditor']) // ID, hogy felismerje
            ->label('Részletes leírás')
            ->tab('Base');

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
                'suffix' => $attribute->suffix,
                'wrapperAttributes' => ['class' => 'col-4'], // wrapper div-hez
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

        UniqueCode::updateCode((int)explode('/', $itemAttributes['property_code'])[0]);

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

        UniqueCode::updateCode((int)explode('/', $itemAttributes['property_code'])[0]);

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

    public function findPropertyOrProject(string $unique_id) {
        $property = Property::where('property_code', $unique_id)->first();

        if (!$property) {
            $project = Project::where('project_code', $unique_id)->first();

            return redirect('/admin/project/' . $project->id . '/edit');

        }

        return redirect('/admin/property/' . $property->id . '/edit');
    }

}
