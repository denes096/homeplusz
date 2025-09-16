<?php

namespace App\Http\Controllers\Admin;

use App\Mail\PropertiesForCustomer;
use App\Models\CustomerOffer;
use App\Models\CustomerSearch;
use App\Models\Property;
use App\Services\PropertyService;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

/**
 * Class CustomersCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class CustomersCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Customers::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/customers');
        CRUD::setEntityNameStrings('customers', 'customers');
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

        /**
         * Columns can be defined using the fluent syntax:
         * - CRUD::column('price')->type('number');
         */
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
        // CRUD::field('azonosito2_0')->type('text')->tab('Alapadatok');
        // CRUD::field('name_1')->type('text')->tab('Alapadatok');
        // CRUD::field('phone_1')->type('text')->tab('Alapadatok');
        // CRUD::field('name_2')->type('text')->tab('Alapadatok');
        // CRUD::field('phone_2')->type('text')->tab('Alapadatok');
        // CRUD::field('name_3')->type('text')->tab('Alapadatok');
        // CRUD::field('phone_3')->type('text')->tab('Alapadatok');
        //        CRUD::field('name_4')->type('text')->tab('Alapadatok');
        //        CRUD::field('phone_4')->type('text')->tab('Alapadatok');
        CRUD::field('email')->type('email')->tab('Alapadatok');
        CRUD::field('note')->type('textarea')->tab('Alapadatok');

        CRUD::addField([
            'label' => 'Min ár',
            'type' => 'number',
            'name' => 'p[price_min]',
            'tab' => 'Keresési paraméterek',
            'attributes' => [
                'class' => 'form-control',
            ], // change the HTML attributes of your input
            'wrapper' => [
                'class' => 'form-group col-md-3',
            ], //
        ]);

        CRUD::addField([
            'label' => 'Max ár',
            'name' => 'p[price_max]',
            'type' => 'number',
            'tab' => 'Keresési paraméterek',
            'attributes' => [
                'class' => 'form-control',
            ], // change the HTML attributes of your input
            'wrapper' => [
                'class' => 'form-group col-md-3',
            ], //
        ]);

        // Ad type (Eladó / Kiadó / Minden)
        CRUD::addField([
            'label' => 'Típus',
            'name' => 'p[ad_type]',
            'type' => 'select_from_array',
            'options' => [
                'Eladó' => 'Eladó',
                'Kiadó' => 'Kiadó',
                'Minden' => 'Minden',
            ],
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);

        // Property types / subtypes / settlements / settlement parts (multiselects)
        CRUD::addField([
            'label' => 'Ingatlantípus',
            'name' => 'p[property_types]',
            'type' => 'select_from_array',
            'options' => \App\Models\PropertyType::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);

        CRUD::addField([
            'label' => 'Ingatlan altípus',
            'name' => 'p[property_subtypes]',
            'type' => 'select_from_array',
            'options' => \App\Models\PropertySubtype::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);

        CRUD::addField([
            'label' => 'Település',
            'name' => 'p[settlements]',
            'type' => 'select_from_array',
            'options' => \App\Models\Settlement::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);

        CRUD::addField([
            'label' => 'Település rész',
            'name' => 'p[settlement_parts]',
            'type' => 'select_from_array',
            'options' => \App\Models\SettlementPart::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-md-3'],
        ]);

        // Dynamic property attributes (numbers, selects, checkboxes)
        $propAttrsCats = \App\Models\PropertyAttributeCategory::with('propertyAttributes')->get();
        foreach ($propAttrsCats as $propAttrCat) {
            $propAttrs = $propAttrCat->propertyAttributes;
            foreach ($propAttrs as $propAttr) {
                if ($propAttr->type == 'number') {
                    CRUD::addField([
                        'label' => $propAttr->label.' min',
                        'name' => 'p['.$propAttr->name.'_min]',
                        'type' => 'number',
                        'tab' => 'Keresési paraméterek',
                        'wrapper' => ['class' => 'form-group col-md-3'],
                    ]);
                    CRUD::addField([
                        'label' => $propAttr->label.' max',
                        'name' => 'p['.$propAttr->name.'_max]',
                        'type' => 'number',
                        'tab' => 'Keresési paraméterek',
                        'wrapper' => ['class' => 'form-group col-md-3'],
                    ]);
                } elseif ($propAttr->type == 'select' || $propAttr->type == 'select_multiple') {
                    $options = json_decode($propAttr->values, true) ?: [];
                    CRUD::addField([
                        'label' => $propAttr->label,
                        'name' => 'p['.$propAttr->name.']',
                        'type' => 'select_from_array',
                        'options' => $options,
                        'allows_multiple' => ($propAttr->type == 'select_multiple'),
                        'tab' => 'Keresési paraméterek',
                        'wrapper' => ['class' => 'form-group col-md-3'],
                    ]);
                } elseif ($propAttr->type == 'checkbox') {
                    CRUD::addField([
                        'label' => $propAttr->label,
                        'name' => 'p['.$propAttr->name.']',
                        'type' => 'checkbox',
                        'tab' => 'Keresési paraméterek',
                        'wrapper' => ['class' => 'form-group col-md-3'],
                    ]);
                }
            }
        }

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

        // Prefill the p[...] fields from the saved CustomerSearch if present
        $entry = $this->crud->getCurrentEntry();
        if ($entry) {
            $search = CustomerSearch::where('customer_id', $entry->id)->first();
            if ($search) {
                $params = json_decode($search->search, true) ?: [];
                foreach ($params as $key => $value) {
                    $fieldName = 'p['.$key.']';
                    // set field value (handles arrays and scalars)
                    CRUD::modifyField($fieldName, ['value' => $value]);
                }
            }
        }
    }

    /**
     * Show a customer with tabs: data and saved searches
     */
    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        $customer = $this->crud->model::with('referens')->findOrFail($id);

        $searches = CustomerSearch::where('customer_id', $id)->get();

        // decode searches into arrays
        foreach ($searches as $s) {
            $s->search_array = json_decode($s->search, true) ?: [];
        }

        return view('vendor.backpack.customers.show', [
            'customer' => $customer,
            'searches' => $searches,
        ]);
    }

    /**
     * Execute a saved search and return matching properties (AJAX)
     */
    public function executeSearch(Request $request, $id, $searchId, PropertyService $propertyService)
    {
        $search = CustomerSearch::where('customer_id', $id)->findOrFail($searchId);
        $params = json_decode($search->search, true) ?: [];

        // Remove the 'p[...]' prefix from parameter keys to match PropertyService expectations
        $cleanParams = [];
        foreach ($params as $key => $value) {
            if (strpos($key, 'p[') === 0 && substr($key, -1) === ']') {
                // Extract the key from p[key] format
                $cleanKey = substr($key, 2, -1);

                // Handle backward compatibility for old field names
                if ($cleanKey === 'min_ar') {
                    $cleanKey = 'price_min';
                } elseif ($cleanKey === 'max_ar') {
                    $cleanKey = 'price_max';
                }

                $cleanParams[$cleanKey] = $value;
            } else {
                $cleanParams[$key] = $value;
            }
        }

        // create a request-like object
        $req = new Request($cleanParams);

        $properties = $propertyService->getPropertiesWithFilters($req, 50);

        return view('vendor.backpack.customers._properties_list', ['properties' => $properties]);
    }

    /**
     * Send property email to customer manually
     */
    public function sendPropertyEmail(Request $request, $customerId)
    {
        $this->crud->hasAccessOrFail('update');

        $customer = $this->crud->model::findOrFail($customerId);
        $propertyIdsRaw = $request->input('property_ids', []);
        $propertyIds = is_array($propertyIdsRaw)
            ? $propertyIdsRaw
            : array_filter(array_map('trim', explode(',', (string) $propertyIdsRaw)));

        $properties = Property::whereIn('id', $propertyIds)->get();

        // simple mail — use a Mailable in real app; here we'll send a basic email
        $to = $customer->email;
        if (empty($to)) {
            return back()->with('error', 'Customer has no email set');
        }

        Mail::to($to)->send(new PropertiesForCustomer($customer, $properties));

        return back()->with('success', 'Email sent');
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

        $searchParams = $request->get('p');
        if (! empty($searchParams)) {
            CustomerSearch::create([
                'customer_id' => $item->id,
                'search' => json_encode($searchParams),
            ]);
        }

        // show a success message
        \Alert::success(trans('backpack::crud.insert_success'))->flash();

        // save the redirect choice for next time
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());

    }

    /**
     * Update an existing customer and persist search params.
     */
    public function update()
    {
        $this->crud->hasAccessOrFail('update');

        $request = $this->crud->validateRequest();
        $this->crud->registerFieldEvents();

        $itemAttributes = $this->crud->getStrippedSaveRequest($request);
        $item = $this->crud->update($this->crud->getCurrentEntryId(), $itemAttributes);

        $searchParams = $request->get('p');
        if (! empty($searchParams)) {
            $search = CustomerSearch::firstOrNew(['customer_id' => $item->id]);
            $search->search = json_encode($searchParams);
            $search->save();
        } else {
            CustomerSearch::where('customer_id', $item->id)->delete();
        }

        \Alert::success(trans('backpack::crud.update_success'))->flash();
        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Send offer email to customer
     */
    public function sendOffer(Request $request, $customerId)
    {
        $this->crud->hasAccessOrFail('update');

        $customer = $this->crud->model::findOrFail($customerId);
        $searchId = $request->input('search_id');
        $propertyIds = $request->input('property_ids', []);

        if (empty($propertyIds)) {
            return response()->json(['success' => false, 'message' => 'Nincs kiválasztott ingatlan'], 400);
        }

        $properties = Property::whereIn('id', $propertyIds)->get();

        if ($properties->isEmpty()) {
            return response()->json(['success' => false, 'message' => 'Nem találhatók ingatlanok'], 400);
        }

        // Send email
        try {
            Mail::to($customer->email)->send(new PropertiesForCustomer($customer, $properties));

            // Save offer record
            CustomerOffer::create([
                'customer_id' => $customerId,
                'customer_search_id' => $searchId,
                'property_ids' => $propertyIds,
                'email_subject' => 'Ingatlan ajánlat - '.$customer->name_0,
                'email_content' => 'Kedves '.$customer->name_0.'! Küldjük Önnek a keresési paramétereinek megfelelő ingatlan ajánlatokat.',
                'sent_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Ajánlat sikeresen elküldve']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hiba történt az email küldése során: '.$e->getMessage()], 500);
        }
    }

    /**
     * Get offers for a customer search
     */
    public function getOffers($customerId, $searchId)
    {
        $offers = CustomerOffer::where('customer_id', $customerId)
            ->where('customer_search_id', $searchId)
            ->with('customer')
            ->orderBy('sent_at', 'desc')
            ->get();

        return view('vendor.backpack.customers._offers_list', ['offers' => $offers]);
    }

    /**
     * Get offer details
     */
    public function getOfferDetails($offerId)
    {
        $offer = CustomerOffer::with(['customer', 'customerSearch'])->findOrFail($offerId);
        $properties = Property::whereIn('id', $offer->property_ids)->get();

        return view('vendor.backpack.customers._offer_details', [
            'offer' => $offer,
            'properties' => $properties,
        ]);
    }
}
