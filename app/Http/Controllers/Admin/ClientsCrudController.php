<?php

namespace App\Http\Controllers\Admin;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\ClientDocument;
use App\Models\Property;
use App\Models\User;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

/**
 * Class ClientsCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ClientsCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Client::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/clients');
        CRUD::setEntityNameStrings('megbízó', 'megbízók');
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
            'name' => 'required|string|max:255',
            'company' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
            'contact_person' => 'nullable|string|max:255',
            'contact_position' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
            'status' => 'required|in:Aktív,Felfüggesztve,Archív',
            'user_id' => 'nullable|exists:users,id',
        ]);

        CRUD::field('name')->label('Megbízó neve')->tab('Alapadatok');
        CRUD::field('company')->label('Cég neve')->tab('Alapadatok');
        CRUD::field('email')->type('email')->label('Email cím')->tab('Alapadatok');
        CRUD::field('phone')->label('Telefonszám')->tab('Alapadatok');
        CRUD::field('mobile')->label('Mobil szám')->tab('Alapadatok');

        CRUD::addField([
            'name' => 'user_id',
            'label' => 'Referens',
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => User::class,
            'allows_null' => true,
            'tab' => 'Alapadatok',
        ]);

        CRUD::field('address')->label('Cím')->tab('Címadatok');
        CRUD::field('city')->label('Város')->tab('Címadatok');
        CRUD::field('zip_code')->label('Irányítószám')->tab('Címadatok');
        CRUD::field('country')->label('Ország')->default('Magyarország')->tab('Címadatok');

        CRUD::field('contact_person')->label('Kapcsolattartó neve')->tab('Kapcsolattartó');
        CRUD::field('contact_position')->label('Beosztás')->tab('Kapcsolattartó');

        CRUD::field('notes')->type('textarea')->label('Megjegyzések')->tab('Egyéb');
        CRUD::field('status')->type('select_from_array')->options([
            'Aktív' => 'Aktív',
            'Felfüggesztve' => 'Felfüggesztve',
            'Archív' => 'Archív',
        ])->default('Aktív')->tab('Alapadatok');

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

    /**
     * Show a client with tabs: data, contacts and documents
     */
    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        $client = $this->crud->model::with('user')->findOrFail($id);

        return view('vendor.backpack.clients.show', [
            'client' => $client,
        ]);
    }

    /**
     * Get contacts widget for the form
     */
    private function getContactsWidget(): string
    {
        $clientId = request()->route('id') ?? 'new';
        $contacts = $clientId !== 'new' ? ClientContact::where('client_id', $clientId)->get() : collect();

        $contactsList = $this->renderContactsList($contacts);

        return '
        <div id="contacts-widget">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Kapcsolattartók kezelése</h5>
                    <p class="text-muted mb-0 mt-2">Itt adhatja meg a megbízó további kapcsolattartóit (pl. feleség, családtagok, stb.).</p>
                </div>
                <div class="card-body">
                    <!-- Add Contact Form -->
                    <div class="mb-4">
                        <div id="contact-form">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="contact-name" class="form-label">Név <small class="text-danger">*</small></label>
                                    <input type="text" class="form-control" id="contact-name" name="contact_name" placeholder="Kapcsolattartó neve">
                                </div>
                                <div class="col-md-2">
                                    <label for="contact-relationship" class="form-label">Kapcsolat</label>
                                    <input type="text" class="form-control" id="contact-relationship" name="contact_relationship" placeholder="pl. feleség, testvér">
                                </div>
                                <div class="col-md-3">
                                    <label for="contact-phone" class="form-label">Telefonszám</label>
                                    <input type="text" class="form-control" id="contact-phone" name="contact_phone" placeholder="Telefonszám">
                                </div>
                                <div class="col-md-3">
                                    <label for="contact-email" class="form-label">Email cím</label>
                                    <input type="email" class="form-control" id="contact-email" name="contact_email" placeholder="email@cim.com">
                                </div>
                                <div class="col-md-1">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="add-contact-btn" class="btn btn-primary d-block w-100">Hozzáadás</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
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

                    fetch("/admin/clients/'.$clientId.'/add-contact", {
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

                fetch("/admin/clients/contact/" + contactId + "/delete", {
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
     * Get documents widget for the form
     */
    private function getDocumentsWidget(): string
    {
        $clientId = request()->route('id') ?? 'new';
        $documents = $clientId !== 'new' ? ClientDocument::where('client_id', $clientId)->get() : collect();

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
                                <div class="col-md-3">
                                    <label for="document-name" class="form-label">Dokumentum neve <small class="text-muted">(opcionális)</small></label>
                                    <input type="text" class="form-control" id="document-name" name="name" placeholder="Ha üres, a fájl neve lesz használva">
                                </div>
                                <div class="col-md-3">
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
                                <div class="col-md-4">
                                    <label for="document-file" class="form-label">Fájl <small class="text-danger">*</small></label>
                                    <input type="file" class="form-control" id="document-file" name="file" accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="button" id="upload-document-btn" class="btn btn-primary d-block w-100">Feltöltés</button>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-12">
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

                    fetch("/admin/clients/'.$clientId.'/upload-document", {
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

                fetch("/admin/clients/document/" + documentId + "/delete", {
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
     * Add a contact to a client
     */
    public function addContact(Request $request, $clientId)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'relationship' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $client = Client::findOrFail($clientId);

            $contact = ClientContact::create([
                'client_id' => $clientId,
                'name' => $request->name,
                'relationship' => $request->relationship,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
                'is_primary' => false,
            ]);

            // Get updated contacts list
            $contacts = ClientContact::where('client_id', $clientId)->get();
            $contactsHtml = $this->renderContactsList($contacts);

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen hozzáadva!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Client contact creation error', [
                'client_id' => $clientId,
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
            $contact = ClientContact::findOrFail($contactId);
            $clientId = $contact->client_id;

            $contact->delete();

            // Get updated contacts list
            $contacts = ClientContact::where('client_id', $clientId)->get();
            $contactsHtml = $this->renderContactsList($contacts);

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen törölve!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Client contact deletion error', [
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
     * Upload a document for a client
     */
    public function uploadDocument(Request $request, $clientId)
    {
        try {
            \Log::info('Client document upload attempt', [
                'client_id' => $clientId,
                'request_data' => $request->all(),
                'files' => $request->files->all(),
            ]);

            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $client = Client::findOrFail($clientId);
            $file = $request->file('file');

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();

            // Store file in client documents directory
            $path = $file->storeAs('client-documents', $filename, 'public');

            // Create document record
            $document = ClientDocument::create([
                'client_id' => $clientId,
                'name' => $request->input('name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            // Get updated documents list
            $documents = ClientDocument::where('client_id', $clientId)->get();
            $documentsHtml = $this->renderDocumentsList($documents);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Client document upload error', [
                'client_id' => $clientId,
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
     * Delete a client document
     */
    public function deleteDocument($documentId)
    {
        try {
            $document = ClientDocument::findOrFail($documentId);
            $clientId = $document->client_id;

            // Delete the document (this will also delete the file via model event)
            $document->delete();

            // Get updated documents list
            $documents = ClientDocument::where('client_id', $clientId)->get();
            $documentsHtml = $this->renderDocumentsList($documents);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Client document delete error', [
                'document_id' => $documentId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a törlés során: '.$e->getMessage(),
            ], 500);
        }
    }
}
