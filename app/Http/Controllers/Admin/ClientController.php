<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientContact;
use App\Models\ClientDocument;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ClientController extends Controller
{
    public function index(): View
    {
        $clients = Client::with('user')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Megbízók',
            'pageTitle' => 'Megbízók',
            'items' => $clients,
            'columns' => [
                ['name' => 'name', 'label' => 'Név'],
                ['name' => 'company', 'label' => 'Cég'],
                ['name' => 'email', 'label' => 'Email'],
                ['name' => 'phone', 'label' => 'Telefon'],
                [
                    'name' => 'user',
                    'label' => 'Referens',
                    'type' => 'relationship',
                    'relationship' => 'user',
                    'attribute' => 'name',
                ],
                ['name' => 'status', 'label' => 'Státusz'],
            ],
            'createRoute' => 'admin.clients.create',
            'editRoute' => 'admin.clients.edit',
            'destroyRoute' => 'admin.clients.destroy',
            'showRoute' => 'admin.clients.show',
        ]);
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.clients.create', [
            'title' => 'Új megbízó',
            'pageTitle' => 'Új megbízó',
            'users' => $users,
            'storeRoute' => 'admin.clients.store',
            'indexRoute' => 'admin.clients.index',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
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

        $client = Client::create($validated);
        
        return redirect()->route('admin.clients.show', $client->id)
            ->with('success', 'Megbízó sikeresen létrehozva.');
    }

    public function show(Client $client): View
    {
        $client->load(['user', 'contacts', 'documents']);
        
        return view('admin.clients.show', [
            'title' => 'Megbízó részletei',
            'pageTitle' => 'Megbízó: ' . $client->name,
            'client' => $client,
            'indexRoute' => 'admin.clients.index',
            'editRoute' => 'admin.clients.edit',
        ]);
    }

    public function edit(Client $client): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.clients.edit', [
            'title' => 'Megbízó szerkesztése',
            'pageTitle' => 'Megbízó szerkesztése',
            'client' => $client,
            'users' => $users,
            'updateRoute' => 'admin.clients.update',
            'indexRoute' => 'admin.clients.index',
        ]);
    }

    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
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

        $client->update($validated);
        
        return redirect()->route('admin.clients.show', $client->id)
            ->with('success', 'Megbízó sikeresen frissítve.');
    }

    public function destroy(Client $client): RedirectResponse
    {
        $client->delete();
        
        return redirect()->route('admin.clients.index')
            ->with('success', 'Megbízó sikeresen törölve.');
    }

    public function addContact(Request $request, $clientId): JsonResponse
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

            ClientContact::create([
                'client_id' => $clientId,
                'name' => $request->name,
                'relationship' => $request->relationship,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
                'is_primary' => false,
            ]);

            $contacts = ClientContact::where('client_id', $clientId)->get();
            $contactsHtml = view('admin.clients._contacts_list', ['contacts' => $contacts])->render();

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

    public function deleteContact($contactId): JsonResponse
    {
        try {
            $contact = ClientContact::findOrFail($contactId);
            $clientId = $contact->client_id;

            $contact->delete();

            $contacts = ClientContact::where('client_id', $clientId)->get();
            $contactsHtml = view('admin.clients._contacts_list', ['contacts' => $contacts])->render();

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

    public function uploadDocument(Request $request, $clientId): JsonResponse
    {
        try {
            $request->validate([
                'document_name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240',
                'description' => 'nullable|string|max:1000',
            ]);

            $client = Client::findOrFail($clientId);
            $file = $request->file('file');

            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('client-documents', $filename, 'public');

            ClientDocument::create([
                'client_id' => $clientId,
                'name' => $request->input('document_name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            $documents = ClientDocument::where('client_id', $clientId)->get();
            $documentsHtml = view('admin.clients._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Client document upload error', [
                'client_id' => $clientId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a feltöltés során: '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteDocument($documentId): JsonResponse
    {
        try {
            $document = ClientDocument::findOrFail($documentId);
            $clientId = $document->client_id;

            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            $documents = ClientDocument::where('client_id', $clientId)->get();
            $documentsHtml = view('admin.clients._documents_list', ['documents' => $documents])->render();

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

