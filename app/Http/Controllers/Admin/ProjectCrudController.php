<?php

namespace App\Http\Controllers\Admin;

use App\Models\UniqueCode;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

/**
 * Class ProjectCrudController
 *
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ProjectCrudController extends CrudController
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
        CRUD::setModel(\App\Models\Project::class);
        CRUD::setRoute(config('backpack.base.route_prefix').'/project');
        CRUD::setEntityNameStrings('projekt', 'projektek');
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
        CRUD::column('name');
        CRUD::column('title');
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
            'title' => 'required|string|max:255',
            'description' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'partner_id' => 'nullable|exists:partners,id',
            'storage_count' => 'required|integer|min:0',
            'storage_type' => 'required|in:fixed,optional',
            'is_required_storage' => 'required|boolean',
        ]);

        CRUD::addField([
            'label' => 'Projekt azonosító',
            'type' => 'number',
            'name' => 'project_code',
            'value' => UniqueCode::getNextCode(),
            'tab' => 'Alapadatok',
        ]);
        CRUD::field('name')->label('Név')->tab('Alapadatok');
        CRUD::field('title')->label('Összefoglaló')->tab('Alapadatok');
        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Leírás')->tab('Alapadatok');

        CRUD::field('images')
            ->label('Képek')
            ->type('upload_multiple')
            ->withFiles(
                [
                    'disk' => 'public', // the disk where file will be stored
                    'path' => 'uploads', // the path inside the disk where file will be stored
                ]
            )->attributes([
                'id' => 'input_images', // 💡 ID hozzáadása a JS miatt
            ])->tab('Alapadatok');

        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
            ->tab('Alapadatok'); // vagy bármi a tab neve;

        CRUD::addField([
            'name' => 'partner_id',
            'label' => 'Partner',
            'type' => 'select',
            'entity' => 'partner',
            'model' => 'App\Models\Partners',
            'attribute' => 'name',
            'allows_null' => true,
            'tab' => 'Alapadatok',
        ]);

        // Tároló információk
        CRUD::field('storage_count')->type('number')->label('Tárolók száma')->default(0)->tab('Tárolók');
        CRUD::field('storage_type')->type('select_from_array')->options([
            'fixed' => 'Fixen hozzárendelt',
            'optional' => 'Bármelyik választható',
        ])->label('Tároló típusa')->default('optional')->tab('Tárolók');
        CRUD::field('is_required_storage')->type('checkbox')->label('Kötelező megvásárolni')->tab('Tárolók');

        CRUD::addField([
            'name' => 'user_id',
            'label' => 'Referens',
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User',
            'allows_null' => true,
            'tab' => 'Alapadatok',
        ]);

        /**
         * Fields can be defined using the fluent syntax:
         * - CRUD::field('price')->type('number');
         */
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
        CRUD::setValidation([
            'name' => 'required|string|max:255',
            'title' => 'required|string|max:255',
            'description' => 'required',
            'user_id' => 'nullable|exists:users,id',
            'partner_id' => 'nullable|exists:partners,id',
            'storage_count' => 'required|integer|min:0',
            'storage_type' => 'required|in:fixed,optional',
            'is_required_storage' => 'required|boolean',
        ]);

        CRUD::addField([
            'label' => 'Projekt azonosító',
            'type' => 'number',
            'name' => 'project_code',
            'tab' => 'Alapadatok',
        ]);
        CRUD::field('name')->label('Név')->tab('Alapadatok');
        CRUD::field('title')->label('Összefoglaló')->tab('Alapadatok');
        CRUD::field('description')
            ->type('textarea')
            ->attributes(['class' => 'ckeditor']) // ID, hogy felismerje
            ->label('Leírás')->tab('Alapadatok');

        CRUD::field('images')
            ->label('Képek')
            ->type('upload_multiple')
            ->withFiles(
                [
                    'disk' => 'public', // the disk where file will be stored
                    'path' => 'uploads', // the path inside the disk where file will be stored
                ]
            )->attributes([
                'id' => 'input_images', // 💡 ID hozzáadása a JS miatt
            ])->tab('Alapadatok');

        CRUD::field('image_preview_helper')
            ->type('custom_html')
            ->value('<div id="image_preview" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 10px;"></div>')
            ->tab('Alapadatok'); // vagy bármi a tab neve;

        CRUD::addField([
            'name' => 'partner_id',
            'label' => 'Partner',
            'type' => 'select',
            'entity' => 'partner',
            'model' => 'App\Models\Partners',
            'attribute' => 'name',
            'allows_null' => true,
            'tab' => 'Alapadatok',
        ]);

        CRUD::addField([
            'name' => 'user_id',
            'label' => 'Referens',
            'type' => 'select',
            'entity' => 'user',
            'attribute' => 'name',
            'model' => 'App\Models\User',
            'allows_null' => true,
            'tab' => 'Alapadatok',
        ]);

        // Tároló információk
        CRUD::field('storage_count')->type('number')->label('Tárolók száma')->default(0)->tab('Tárolók');
        CRUD::field('storage_type')->type('select_from_array')->options([
            'fixed' => 'Fixen hozzárendelt',
            'optional' => 'Bármelyik választható',
        ])->label('Tároló típusa')->default('optional')->tab('Tárolók');
        CRUD::field('is_required_storage')->type('checkbox')->label('Kötelező megvásárolni')->tab('Tárolók');

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

        UniqueCode::updateCode((int) $itemAttributes['project_code']);

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

        UniqueCode::updateCode((int) $itemAttributes['project_code']);

        $this->data['entry'] = $this->crud->entry = $item;

        \Alert::success(trans('backpack::crud.update_success'))->flash();

        $this->crud->setSaveAction();

        return $this->crud->performSaveAction($item->getKey());
    }

    /**
     * Get documents widget for the show view
     */
    private function getDocumentsWidgetForShow(): string
    {
        $projectId = request()->route('id') ?? 'new';
        if ($projectId === 'new') {
            return '<div class="alert alert-info">Dokumentumok csak a projekt mentése után érhetők el.</div>';
        }

        $documents = \App\Models\ProjectDocument::where('project_id', $projectId)->get();

        if ($documents->isEmpty()) {
            return '
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Dokumentumok kezelése</h5>
                </div>
                <div class="card-body text-center">
                    <p class="text-muted">Még nincsenek feltöltött dokumentumok.</p>
                    <a href="#" class="btn btn-primary" onclick="alert(\'Dokumentum feltöltés funkció fejlesztés alatt.\')">
                        <i class="la la-plus"></i> Új dokumentum feltöltése
                    </a>
                </div>
            </div>';
        }

        $documentsHtml = '<div class="table-responsive"><table class="table table-striped">';
        $documentsHtml .= '<thead><tr><th>Név</th><th>Kategória</th><th>Fájl</th><th>Méret</th><th>Feltöltve</th><th>Műveletek</th></tr></thead><tbody>';

        foreach ($documents as $document) {
            $documentsHtml .= '<tr>';
            $documentsHtml .= '<td>'.htmlspecialchars($document->name ?? 'Nincs név').'</td>';
            $documentsHtml .= '<td><span class="badge bg-secondary">'.htmlspecialchars($document->category_name ?? 'Ismeretlen').'</span></td>';
            $documentsHtml .= '<td><a href="'.Storage::url($document->file_path).'" target="_blank">'.htmlspecialchars($document->original_name).'</a></td>';
            $documentsHtml .= '<td>'.htmlspecialchars($document->file_size_human ?? '0 B').'</td>';
            $documentsHtml .= '<td>'.htmlspecialchars($document->created_at->format('Y-m-d H:i')).'</td>';
            $documentsHtml .= '<td>';
            $documentsHtml .= '<button class="btn btn-sm btn-outline-danger" onclick="if(confirm(\'Biztosan törölni szeretné ezt a dokumentumot?\')) deleteDocument('.$document->id.')">Törlés</button>';
            $documentsHtml .= '</td>';
            $documentsHtml .= '</tr>';
        }

        $documentsHtml .= '</tbody></table></div>';

        return '
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Dokumentumok kezelése</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <a href="#" class="btn btn-primary" onclick="alert(\'Dokumentum feltöltés funkció fejlesztés alatt.\')">
                        <i class="la la-plus"></i> Új dokumentum feltöltése
                    </a>
                </div>
                '.$documentsHtml.'
            </div>
        </div>';
    }

    /**
     * Get simplified documents widget for the form
     */
    private function getSimplifiedDocumentsWidget(): string
    {
        $projectId = request()->route('id') ?? 'new';
        $documents = $projectId !== 'new' ? \App\Models\ProjectDocument::where('project_id', $projectId)->get() : collect();

        $documentsList = $this->renderDocumentsList($documents);

        return '
        <div id="documents-widget">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Dokumentumok kezelése</h5>
                    <p class="text-muted mb-0 mt-2">A dokumentumok kezelése külön oldalon keresztül érhető el a projekt megtekintésekor.</p>
                </div>
                <div class="card-body">
                    <!-- Documents List -->
                    <div id="documents-list">
                        '.$documentsList.'
                    </div>
                </div>
            </div>
        </div>';
    }

    /**
     * Get documents widget for the form (original complex version)
     */
    private function getDocumentsWidget(): string
    {
        $projectId = request()->route('id') ?? 'new';
        $documents = $projectId !== 'new' ? \App\Models\ProjectDocument::where('project_id', $projectId)->get() : collect();

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
                                        <option value="blueprint">Alaprajz</option>
                                        <option value="contract">Megbízás</option>
                                        <option value="foundation">TH alapító okirat</option>
                                        <option value="permit">Engedélyek</option>
                                        <option value="invoice">Számlák</option>
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

                    fetch("/admin/project/'.$projectId.'/upload-document", {
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
                            alert("Hiba a feltöltés során: " + (data.message || "Ismeretlen hiba"));
                        }
                    })
                    .catch(error => {
                        console.error("Upload error:", error);
                        alert("Hiba a feltöltés során: " + error.message);
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

                fetch("/admin/project/document/" + documentId + "/delete", {
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
                        alert("Hiba a törlés során: " + (data.message || "Ismeretlen hiba"));
                    }
                })
                .catch(error => {
                    console.error("Delete error:", error);
                    alert("Hiba a törlés során: " + error.message);
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
            $html .= '<td>'.htmlspecialchars($document->name).'</td>';
            $html .= '<td><span class="badge bg-secondary">'.$document->category_name.'</span></td>';
            $html .= '<td><a href="'.$document->file_url.'" target="_blank">'.htmlspecialchars($document->original_name).'</a></td>';
            $html .= '<td>'.$document->file_size_human.'</td>';
            $html .= '<td>'.$document->created_at->format('Y-m-d H:i').'</td>';
            $html .= '<td>';
            $html .= '<button class="btn btn-sm btn-danger" onclick="deleteDocument('.$document->id.')">Törlés</button>';
            $html .= '</td>';
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';

        return $html;
    }

    /**
     * Upload a document for a project
     */
    public function uploadDocument(Request $request, $projectId)
    {
        try {
            \Log::info('Document upload attempt', [
                'project_id' => $projectId,
                'request_data' => $request->all(),
                'files' => $request->files->all(),
            ]);

            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:blueprint,contract,foundation,permit,invoice,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $project = \App\Models\Project::findOrFail($projectId);
            $file = $request->file('file');

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();

            // Store file in documents directory
            $path = $file->storeAs('documents/projects', $filename, 'public');

            // Create document record
            $document = \App\Models\ProjectDocument::create([
                'project_id' => $projectId,
                'name' => $request->input('name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            // Get updated documents list
            $documents = \App\Models\ProjectDocument::where('project_id', $projectId)->get();
            $documentsHtml = $this->renderDocumentsList($documents);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Document upload error', [
                'project_id' => $projectId,
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
     * Delete a document
     */
    public function deleteDocument($documentId)
    {
        try {
            $document = \App\Models\ProjectDocument::findOrFail($documentId);
            $projectId = $document->project_id;

            // Delete the document (this will also delete the file via model event)
            $document->delete();

            // Get updated documents list
            $documents = \App\Models\ProjectDocument::where('project_id', $projectId)->get();
            $documentsHtml = $this->renderDocumentsList($documents);

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Document delete error', [
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
     * Setup the show operation with documents widget
     */
    protected function setupShowOperation()
    {
        // Add documents widget to the show view
        CRUD::addField([
            'name' => 'documents',
            'type' => 'custom_html',
            'value' => $this->getDocumentsWidgetForShow(),
        ]);
    }

    /**
     * Show a project with documents management
     */
    public function show($id)
    {
        $this->crud->hasAccessOrFail('show');

        // Get the entry using the parent's logic
        $entry = $this->crud->getEntryWithLocale($id);

        // Load documents for this project
        $documents = \App\Models\ProjectDocument::where('project_id', $id)->get();

        $this->data['entry'] = $entry;
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? trans('backpack::crud.preview').' '.$this->crud->entity_name;
        $this->data['documents'] = $documents;

        // Load the standard Backpack show view
        return view('vendor.backpack.crud.project_show', $this->data);
    }
}
