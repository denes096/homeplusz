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
use Illuminate\Support\Facades\Storage;

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
        CRUD::setEntityNameStrings('vevő', 'vevők');
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
        CRUD::setFromDb(); // oszlopok beállítása az adatbázis oszlopok alapján
        if (request()->has('refId')) {
            $this->crud->addClause('where', 'refId', request()->input('refId'));
        }
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
            'kategoria' => 'required|in:maganszemely,beruhazo',
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
            'address' => 'nullable|string|max:255',
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
            'maganszemely' => 'Magánszemély',
            'beruhazo' => 'Beruházó',
        ])->default('maganszemely')->tab('Alapadatok');

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
        CRUD::field('address')->type('text')->label('Cím')->tab('Alapadatok');
        CRUD::field('note')->type('textarea')->tab('Alapadatok');

        // Kapcsolattartók fül
        CRUD::addField([
            'name' => 'contacts',
            'type' => 'custom_html',
            'value' => $this->getContactsWidget(),
            'tab' => 'Kapcsolattartók',
        ]);

        // Dokumentumok fül
        CRUD::addField([
            'name' => 'documents',
            'type' => 'custom_html',
            'value' => $this->getDocumentsWidget(),
            'tab' => 'Dokumentumok',
        ]);

        CRUD::addField([
            'label' => 'Min ár',
            'type' => 'number',
            'name' => 'p[price_min]',
            'tab' => 'Keresési paraméterek',
            'attributes' => [
                'class' => 'form-control',
            ], // change the HTML attributes of your input
            'wrapper' => [
                'class' => 'form-group col-sm-6 col-md-3',
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
                'class' => 'form-group col-sm-6 col-md-3',
            ], //
        ]);

        // Hirdetés típusa (Eladó / Kiadó / Minden)
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
            'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
        ]);

        // Ingatlan típusok / altípusok / települések / településrészek (többszörös választás)
        CRUD::addField([
            'label' => 'Ingatlantípus',
            'name' => 'p[property_types]',
            'type' => 'select_from_array',
            'options' => \App\Models\PropertyType::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
        ]);

        CRUD::addField([
            'label' => 'Ingatlan altípus',
            'name' => 'p[property_subtypes]',
            'type' => 'select_from_array',
            'options' => \App\Models\PropertySubtype::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
        ]);

        CRUD::addField([
            'label' => 'Település',
            'name' => 'p[settlements]',
            'type' => 'select_from_array',
            'options' => \App\Models\Settlement::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
        ]);

        CRUD::addField([
            'label' => 'Település rész',
            'name' => 'p[settlement_parts]',
            'type' => 'select_from_array',
            'options' => \App\Models\SettlementPart::all()->pluck('name', 'id')->toArray(),
            'allows_multiple' => true,
            'tab' => 'Keresési paraméterek',
            'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
        ]);

        // Dinamikus ingatlan attribútumok (számok, választók, jelölőnégyzetek)
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
                        'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
                    ]);
                    CRUD::addField([
                        'label' => $propAttr->label.' max',
                        'name' => 'p['.$propAttr->name.'_max]',
                        'type' => 'number',
                        'tab' => 'Keresési paraméterek',
                        'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
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
                        'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
                    ]);
                } elseif ($propAttr->type == 'checkbox') {
                    CRUD::addField([
                        'label' => $propAttr->label,
                        'name' => 'p['.$propAttr->name.']',
                        'type' => 'checkbox',
                        'tab' => 'Keresési paraméterek',
                        'wrapper' => ['class' => 'form-group col-sm-6 col-md-3'],
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

        // A p[...] mezők előtöltése a mentett CustomerSearch alapján, ha van
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

        // egyszerű email — valódi alkalmazásban Mailable használj; itt egy alapvető emailt küldünk
        $to = $customer->email;
        if (empty($to)) {
            return back()->with('error', 'A vevőnek nincs beállítva email cím');
        }

        Mail::to($to)->send(new PropertiesForCustomer($customer, $properties));

        return back()->with('success', 'Email elküldve');
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
     * Get contacts widget for the form
     */
    private function getContactsWidget(): string
    {
        $customerId = request()->route('id') ?? 'new';
        $contacts = $customerId !== 'new' ? \App\Models\CustomerContact::where('customer_id', $customerId)->get() : collect();

        $contactsList = $this->renderContactsList($contacts);

        return '
        <div id="contacts-widget">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Kapcsolattartók kezelése</h5>
                    <p class="text-muted mb-0 mt-2">Itt adhatja meg a vevő további kapcsolattartóit (pl. feleség, családtagok, stb.).</p>
                </div>
                <div class="card-body">
                    <!-- Add Contact Form -->
                    <div class="mb-4">
                        <div id="contact-form">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <label for="contact-name" class="form-label">Név <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" id="contact-name" name="contact_name" placeholder="Kapcsolattartó neve">
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <label for="contact-relationship" class="form-label">Kapcsolat</label>
                                    <input type="text" class="form-control" id="contact-relationship" name="contact_relationship" placeholder="pl. feleség, testvér">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="contact-phone" class="form-label">Telefonszám</label>
                                    <input type="text" class="form-control" id="contact-phone" name="contact_phone" placeholder="Telefonszám">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="contact-email" class="form-label">Email cím</label>
                                    <input type="email" class="form-control" id="contact-email" name="contact_email" placeholder="email@cim.com">
                                </div>
                                <div class="col-sm-6 col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="add-contact-btn" class="btn btn-primary d-block w-100">Hozzáadás</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label for="contact-notes" class="form-label">Megjegyzések</label>
                                    <textarea class="form-control" id="contact-notes" name="contact_notes" rows="2" placeholder="Egyéb megjegyzések a kapcsolattartóról"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Contacts List -->
                    <div id="contacts-list">
                        '.$contactsList.'
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const addBtn = document.getElementById("add-contact-btn");
            const contactsList = document.getElementById("contacts-list");

            if (addBtn) {
                addBtn.addEventListener("click", function(e) {
                    e.preventDefault();
                    e.stopPropagation();

                    // Get form values
                    const name = document.getElementById("contact-name").value;
                    const relationship = document.getElementById("contact-relationship").value;
                    const phone = document.getElementById("contact-phone").value;
                    const email = document.getElementById("contact-email").value;
                    const notes = document.getElementById("contact-notes").value;

                    // Validation
                    if (!name.trim()) {
                        alert("A kapcsolattartó neve kötelező!");
                        return;
                    }

                    // Create FormData
                    const formData = new FormData();
                    formData.append("name", name);
                    formData.append("relationship", relationship);
                    formData.append("phone", phone);
                    formData.append("email", email);
                    formData.append("notes", notes);
                    formData.append("_token", document.querySelector(\'meta[name="csrf-token"]\').getAttribute("content"));

                    fetch("/admin/customers/'.$customerId.'/add-contact", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Kapcsolattartó sikeresen hozzáadva!");
                            // Refresh the contacts list
                            contactsList.innerHTML = data.contacts_html;
                            // Clear form
                            document.getElementById("contact-name").value = "";
                            document.getElementById("contact-relationship").value = "";
                            document.getElementById("contact-phone").value = "";
                            document.getElementById("contact-email").value = "";
                            document.getElementById("contact-notes").value = "";
                        } else {
                            alert("Hiba: " + (data.message || "Ismeretlen hiba"));
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        alert("Hiba történt a hozzáadás során");
                    });
                });
            }

            // Add delete functionality to existing buttons
            window.deleteContact = function(contactId) {
                if (!confirm("Biztosan törölni szeretné ezt a kapcsolattartót?")) {
                    return;
                }

                fetch("/admin/customers/contact/" + contactId + "/delete", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').getAttribute("content"),
                        "Content-Type": "application/json",
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Kapcsolattartó sikeresen törölve!");
                        contactsList.innerHTML = data.contacts_html;
                    } else {
                        alert("Hiba: " + (data.message || "Ismeretlen hiba"));
                    }
                })
                .catch(error => {
                    console.error("Delete error:", error);
                    alert("Hiba történt a törlés során");
                });
            };
        });
        </script>';
    }

    /**
     * Render contacts list HTML
     */
    private function renderContactsList($contacts): string
    {
        if ($contacts->isEmpty()) {
            return '<p class="text-muted">Még nincsenek hozzáadott kapcsolattartók.</p>';
        }

        $html = '<div class="table-responsive"><table class="table table-striped">';
        $html .= '<thead><tr><th>Név</th><th>Kapcsolat</th><th>Telefon</th><th>Email</th><th>Megjegyzések</th><th>Műveletek</th></tr></thead><tbody>';

        foreach ($contacts as $contact) {
            $html .= '<tr>';
            $html .= '<td>'.htmlspecialchars($contact->name).'</td>';
            $html .= '<td>'.htmlspecialchars($contact->relationship ?? '-').'</td>';
            $html .= '<td>'.htmlspecialchars($contact->phone ?? '-').'</td>';
            $html .= '<td>'.htmlspecialchars($contact->email ?? '-').'</td>';
            $html .= '<td>'.htmlspecialchars(substr($contact->notes ?? '-', 0, 50)).(strlen($contact->notes ?? '') > 50 ? '...' : '').'</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-danger" onclick="deleteContact('.$contact->id.')">Törlés</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Add a contact to a customer
     */
    public function addContact(Request $request, $customerId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'relationship' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $customer = \App\Models\Customers::findOrFail($customerId);

            $contact = \App\Models\CustomerContact::create([
                'customer_id' => $customerId,
                'name' => $request->name,
                'relationship' => $request->relationship,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
                'is_primary' => false,
            ]);

            // Get updated contacts list
            $contacts = \App\Models\CustomerContact::where('customer_id', $customerId)->get();
            $contactsHtml = $this->renderContactsList($contacts);

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen hozzáadva!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Contact creation error', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a hozzáadás során: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a contact
     */
    public function deleteContact($contactId)
    {
        try {
            $contact = \App\Models\CustomerContact::findOrFail($contactId);
            $customerId = $contact->customer_id;

            $contact->delete();

            // Get updated contacts list
            $contacts = \App\Models\CustomerContact::where('customer_id', $customerId)->get();
            $contactsHtml = $this->renderContactsList($contacts);

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen törölve!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Contact deletion error', [
                'contact_id' => $contactId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a törlés során: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get documents widget for the form
     */
    private function getDocumentsWidget(): string
    {
        $customerId = request()->route('id') ?? 'new';
        $documents = $customerId !== 'new' ? \App\Models\CustomerDocument::where('customer_id', $customerId)->get() : collect();

        return '
        <div id="documents-widget">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dokumentumok kezelése</h5>
                </div>
                <div class="card-body">
                    <!-- Upload Form -->
                    <div class="mb-4">
                        <div id="document-upload-form">
                            <div class="row">
                                <div class="col-sm-6 col-md-3">
                                    <label for="document-name" class="form-label">Dokumentum neve <small class="text-muted">(opcionális)</small></label>
                                    <input type="text" class="form-control" id="document-name" name="name" placeholder="Ha üres, a fájl neve lesz használva">
                                </div>
                                <div class="col-sm-6 col-md-3">
                                    <label for="document-category" class="form-label">Kategória <small class="text-muted">(opcionális)</small></label>
                                    <select class="form-select" id="document-category" name="category">
                                        <option value="">Válassz kategóriát</option>
                                        <option value="contract">Szerződés</option>
                                        <option value="order">Megrendelő</option>
                                        <option value="purchase">Vételi</option>
                                        <option value="inspection">Szemle</option>
                                        <option value="other">Egyéb</option>
                                    </select>
                                </div>
                                <div class="col-sm-6 col-md-4">
                                    <label for="document-file" class="form-label">Fájl <small class="text-danger">*</small></label>
                                    <input type="file" class="form-control" id="document-file" name="file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-sm-6 col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="upload-document-btn" class="btn btn-primary d-block w-100">Feltöltés</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-12">
                                    <label for="document-description" class="form-label">Leírás (opcionális)</label>
                                    <textarea class="form-control" id="document-description" name="description" rows="2"></textarea>
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

                    fetch("/admin/customers/'.$customerId.'/upload-document", {
                        method: "POST",
                        body: formData
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Dokumentum sikeresen feltöltve!");
                            // Refresh the documents list
                            documentsList.innerHTML = data.documents_html;
                        } else {
                            alert("Hiba: " + (data.message || "Ismeretlen hiba"));
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
                        // Clear form
                        document.getElementById("document-name").value = "";
                        document.getElementById("document-category").value = "";
                        document.getElementById("document-file").value = "";
                        document.getElementById("document-description").value = "";
                    });
                });
            }

            // Add delete functionality to existing buttons
            window.deleteDocument = function(documentId) {
                if (!confirm("Biztosan törölni szeretné ezt a dokumentumot?")) {
                    return;
                }

                fetch("/admin/customers/document/" + documentId + "/delete", {
                    method: "DELETE",
                    headers: {
                        "X-CSRF-TOKEN": document.querySelector(\'meta[name="csrf-token"]\').getAttribute("content"),
                        "Content-Type": "application/json",
                    },
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert("Dokumentum sikeresen törölve!");
                        documentsList.innerHTML = data.documents_html;
                    } else {
                        alert("Hiba: " + (data.message || "Ismeretlen hiba"));
                    }
                })
                .catch(error => {
                    console.error("Delete error:", error);
                    alert("Hiba történt a törlés során");
                });
            };
        });
        </script>';
    }

    /**
     * Render documents list HTML
     */
    private function renderDocumentsList($documents): string
    {
        if ($documents->isEmpty()) {
            return '<p class="text-muted">Még nincsenek feltöltött dokumentumok.</p>';
        }

        $html = '<div class="table-responsive"><table class="table table-striped">';
        $html .= '<thead><tr><th>Név</th><th>Kategória</th><th>Fájl</th><th>Méret</th><th>Feltöltve</th><th>Műveletek</th></tr></thead><tbody>';

        foreach ($documents as $document) {
            $html .= '<tr>';
            $html .= '<td>'.htmlspecialchars($document->name ?? 'Nincs név').'</td>';
            $html .= '<td><span class="badge bg-secondary">'.htmlspecialchars($document->category_name ?? 'Ismeretlen').'</span></td>';
            $html .= '<td><a href="'.Storage::url($document->file_path).'" target="_blank">'.htmlspecialchars($document->original_name).'</a></td>';
            $html .= '<td>'.htmlspecialchars($document->file_size_human ?? '0 B').'</td>';
            $html .= '<td>'.htmlspecialchars($document->created_at->format('Y-m-d H:i')).'</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-outline-danger" onclick="deleteDocument('.$document->id.')">Törlés</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Upload a document for a customer
     */
    public function uploadDocument(Request $request, $customerId)
    {
        try {
            \Log::info('Customer document upload attempt', [
                'customer_id' => $customerId,
                'request_data' => $request->all(),
                'files' => $request->files->all(),
            ]);

            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $customer = \App\Models\Customers::findOrFail($customerId);
            $file = $request->file('file');

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();

            // Store file in customer documents directory
            $path = $file->storeAs('customer-documents', $filename, 'public');

            // Create document record
            $document = \App\Models\CustomerDocument::create([
                'customer_id' => $customerId,
                'name' => $request->input('name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            // Get updated documents list
            $documents = \App\Models\CustomerDocument::where('customer_id', $customerId)->get();
            $documentsHtml = $this->renderDocumentsList($documents);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Customer document upload error', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a feltöltés során: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete a customer document
     */
    public function deleteDocument($documentId)
    {
        try {
            $document = \App\Models\CustomerDocument::findOrFail($documentId);
            $customerId = $document->customer_id;

            // Delete the document (this will also delete the file via model event)
            $document->delete();

            // Get updated documents list
            $documents = \App\Models\CustomerDocument::where('customer_id', $customerId)->get();
            $documentsHtml = $this->renderDocumentsList($documents);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Customer document delete error', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a törlés során: '.$e->getMessage(),
            ], 500);
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
