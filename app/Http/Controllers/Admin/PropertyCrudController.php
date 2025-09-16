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
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;

    public function setup()
    {
        CRUD::setModel(\App\Models\Property::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/property');
        CRUD::setEntityNameStrings('property', 'properties');
    }

    protected function setupListOperation()
    {
        CRUD::setValidation(PropertyRequest::class);
        $this->crud->addButtonFromModelFunction('line', 'toggleActive', 'getToggleActiveButton', 'end');
        $this->crud->addButtonFromModelFunction('line', 'matchingSearches', 'getMatchingSearchesButton', 'end');

        // Backpack CRUD handles search automatically via searchableTable
        // No need for custom search logic here

        $this->crud->addColumns([
            [
                'name' => 'first_image_url',
                'label' => 'Kép',
                'type' => 'image',
                'prefix' => 'storage/', // mert az accessorban már nincs storage prefix
                'height' => '200px',
                'width' => '200px',
            ],
            [
                'name' => 'is_active',
                'label' => 'Aktív',
                'type' => 'checkbox',
                'default' => false,
                'tab' => 'Base',
            ],
            [
                'name' => 'property_code',
                'label' => 'Ingatlan kód',
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
                'type' => 'select_grouped', // https://github.com/Laravel-Backpack/CRUD/issues/502
                'name' => 'settlement_part_id',
                'entity' => 'settlementPart',
                'attribute' => 'name',
                'model' => SettlementPart::class,
                'group_by' => 'settlement', // the relationship to entity you want to use for grouping
                'group_by_attribute' => 'fullName', // the attribute on related model, that you want shown
                'group_by_relationship_back' => 'parts', // relationship from related model back to this model
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
            'tab' => 'Base',
        ]
        );

        CRUD::addField([
            'name' => 'featured',
            'label' => 'Kiemelt',
            'type' => 'checkbox',
            'default' => false,
            'tab' => 'Base',
        ]
        );
        CRUD::field('title')->type('text')->label('Cím')->tab('Base');
        CRUD::field('price')->type('number')->label('Irányár')->suffix('Ft')->tab('Base')->attributes([
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
            'tab' => 'Base',
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
            'label' => 'Ingatlan azonosító',
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
            'type' => 'select_grouped', // https://github.com/Laravel-Backpack/CRUD/issues/502
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
            'type' => 'select_grouped', // https://github.com/Laravel-Backpack/CRUD/issues/502
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
            'label' => 'labels',
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

        // Elrejtjük a már feltöltött fájlok megjelenítését
        CRUD::field('hide_existing_files_css')
            ->type('custom_html')
            ->value('<style>.well.well-sm.existing-file.mb-2 { display: none !important; }</style>')
            ->tab('Base');

        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Részletes leírás')
            ->tab('Base');

        CRUD::field('inner_comments')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor'])
            ->label('Belső komment')
            ->tab('Base');

        // Térkép mezők
        CRUD::addField([
            'name' => 'address',
            'label' => 'Teljes cím (térkép)',
            'type' => 'text',
            'tab' => 'Base',
            'hint' => 'Adja meg a teljes címet, amelyet a térkép megjelenítéshez használunk',
        ]);

        CRUD::addField([
            'name' => 'latitude',
            'label' => 'Szélesség (Latitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 47.4979',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
        ]);

        CRUD::addField([
            'name' => 'longitude',
            'label' => 'Hosszúság (Longitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 19.0402',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
        ]);

        // OpenStreetMap + Leaflet térkép
        CRUD::addField([
            'name' => 'leaflet_map_widget',
            'type' => 'custom_html',
            'value' => $this->getLeafletMapWidget(),
            'tab' => 'Base',
        ]);

        foreach (PropertyAttribute::all() as $attribute) {
            switch ($attribute->type) {
                case 'checkbox':
                    CRUD::addField([
                        'label' => $attribute->label,
                        'type' => 'checkbox',
                        'name' => 'properties['.$attribute->id.']',
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
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                    ]);
                    break;

                case 'number':
                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'number',
                        'prefix' => $attribute->prefix,
                        'suffix' => $attribute->suffix,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
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
                        'label' => $attribute->name,
                        'type' => 'select_from_array',
                        'options' => $values,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
                    ]);
                    break;
                case 'select_multiple':
                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'select_from_array',
                        'name' => 'properties['.$attribute->id.']',
                        'options' => (array) json_decode($attribute->values),
                        'tab' => $attribute->category->name,
                        'allows_multiple' => true,
                    ]);
                    break;
                default:
                    CRUD::addField([
                        'label' => $attribute->name,
                        'type' => 'text',
                        'prefix' => $attribute->prefix,
                        'suffix' => $attribute->suffix,
                        'name' => 'properties['.$attribute->id.']',
                        'tab' => $attribute->category->name,
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
            'label' => 'aktív',
            'type' => 'checkbox',
            'default' => true,
            'tab' => 'Base',
        ]
        );
        CRUD::addField([
            'name' => 'featured',
            'label' => 'Kiemelt',
            'type' => 'checkbox',
            'default' => false,
            'tab' => 'Base',
        ]
        );
        CRUD::field('title')->type('text')->label('Cím')->tab('Base');
        CRUD::field('price')->type('number')->label('Irányár')->suffix('Ft')->tab('Base')->attributes([
            'step' => '1', // ez engedélyezi a tizedes számokat
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
            'tab' => 'Base',
        ]);
        CRUD::addField([
            'label' => 'Ingatlan azonosító',
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
            'type' => 'select_grouped', // https://github.com/Laravel-Backpack/CRUD/issues/502
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
            'type' => 'select_grouped', // https://github.com/Laravel-Backpack/CRUD/issues/502
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
            'label' => 'labels',
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

        // Elrejtjük a már feltöltött fájlok megjelenítését
        CRUD::field('hide_existing_files_css')
            ->type('custom_html')
            ->value('<style>.well.well-sm.existing-file.mb-2 { display: none !important; }</style>')
            ->tab('Base');

        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Részletes leírás')
            ->tab('Base');

        CRUD::field('inner_comments')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor'])
            ->label('Belső komment')
            ->tab('Base');

        // Térkép mezők
        CRUD::addField([
            'name' => 'address',
            'label' => 'Teljes cím (térkép)',
            'type' => 'text',
            'tab' => 'Base',
            'hint' => 'Adja meg a teljes címet, amelyet a térkép megjelenítéshez használunk',
        ]);

        CRUD::addField([
            'name' => 'latitude',
            'label' => 'Szélesség (Latitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 47.4979',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
        ]);

        CRUD::addField([
            'name' => 'longitude',
            'label' => 'Hosszúság (Longitude)',
            'type' => 'number',
            'tab' => 'Base',
            'attributes' => [
                'step' => 'any',
                'placeholder' => 'pl. 19.0402',
            ],
            'hint' => 'Automatikusan kitöltődik a cím alapján',
        ]);

        // OpenStreetMap + Leaflet térkép
        CRUD::addField([
            'name' => 'leaflet_map_widget',
            'type' => 'custom_html',
            'value' => $this->getLeafletMapWidget(),
            'tab' => 'Base',
        ]);

        $propertyId = Route::current()->parameter('id');
        $property = \App\Models\Property::with('attributes')->findOrFail($propertyId);

        foreach ($property->attributes as $attribute) {
            $field = [
                'name' => 'properties['.$attribute->id.']', // pl. attribute_5
                'label' => $attribute->label,
                'type' => $this->mapAttributeType($attribute->type),
                'value' => $attribute->pivot->value,
                'tab' => $attribute->category->name,
                'prefix' => $attribute->prefix,
                'suffix' => $attribute->suffix,
                'wrapperAttributes' => ['class' => 'col-4'], // wrapper div-hez
            ];

            // ha select, akkor a JSON értékek alapján adjunk meg opciókat
            if (in_array($attribute->type, ['select', 'radio'])) {
                $values = (array) json_decode($attribute->values, true);
                //                if (isset($values[0]['id'])) {
                //                    $values = array_combine(array_column($values, 'id'), array_column($values, 'label'));
                //                } else {
                //                    $values = array_combine($values, $values);
                //                }
                foreach ($values as $k => $value) {
                    $values[(string) $k] = (string) $value;
                }

                $field['options'] = $values;
            }
            if ($attribute->type == 'select_multiple') {
                $values = (array) json_decode($attribute->values, true);
                //                if (isset($values[0]['id'])) {
                //                    $values = array_combine(array_column($values, 'id'), array_column($values, 'label'));
                //                } else {
                //                    $values = array_combine($values, $values);
                //                }
                foreach ($values as $k => $value) {
                    $values[(int) $k] = (string) $value;
                }

                $field['allows_multiple'] = true;
                $field['attributes'] = ['multiple' => 'multiple'];
                $field['options'] = $values;
                $field['value'] = json_decode($attribute->pivot->value, true);
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
            'select_multiple' => 'select_from_array',
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

        UniqueCode::updateCode((int) explode('/', $itemAttributes['property_code'])[0]);

        foreach ($request->get('properties') as $attributeId => $attributeValue) {
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

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    public function findPropertyOrProject(string $unique_id)
    {
        $property = Property::where('property_code', $unique_id)->first();

        if (! $property) {
            $project = Project::where('project_code', $unique_id)->first();

            return redirect('/admin/project/'.$project->id.'/edit');

        }

        return redirect('/admin/property/'.$property->id.'/edit');
    }

    private function getLeafletMapWidget(): string
    {
        return '
            <!-- Leaflet CSS -->
            <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>

            <div id="leaflet-map-container" style="margin-top: 20px;">
                <div id="map" style="height: 400px; width: 100%; border: 1px solid #ddd; border-radius: 4px;"></div>
                <div style="margin-top: 10px;">
                    <button type="button" id="get-current-location" class="btn btn-sm btn-info">Jelenlegi helyzet meghatározása</button>
                    <button type="button" id="search-address" class="btn btn-sm btn-primary">Cím keresése</button>
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
}
