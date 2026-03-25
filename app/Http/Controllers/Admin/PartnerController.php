<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PartnerContact;
use App\Models\PartnerDocument;
use App\Models\Partners;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PartnerController extends Controller
{
    public function index(): View
    {
        $partners = Partners::with('user')->latest()->paginate(20);
        
        return view('admin.crud.index', [
            'title' => 'Partnerek',
            'pageTitle' => 'Partnerek',
            'items' => $partners,
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
            'createRoute' => 'admin.partners.create',
            'editRoute' => 'admin.partners.edit',
            'destroyRoute' => 'admin.partners.destroy',
            'showRoute' => 'admin.partners.show',
        ]);
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.partners.create', [
            'title' => 'Új partner',
            'pageTitle' => 'Új partner',
            'users' => $users,
            'storeRoute' => 'admin.partners.store',
            'indexRoute' => 'admin.partners.index',
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

        $partner = Partners::create($validated);
        
        return redirect()->route('admin.partners.show', $partner->id)
            ->with('success', 'Partner sikeresen létrehozva.');
    }

    public function show(Partners $partner): View
    {
        $partner->load(['user', 'contacts', 'documents']);
        
        return view('admin.partners.show', [
            'title' => 'Partner részletei',
            'pageTitle' => 'Partner: ' . $partner->name,
            'partner' => $partner,
            'indexRoute' => 'admin.partners.index',
            'editRoute' => 'admin.partners.edit',
        ]);
    }

    public function edit(Partners $partner): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.partners.edit', [
            'title' => 'Partner szerkesztése',
            'pageTitle' => 'Partner szerkesztése',
            'partner' => $partner,
            'users' => $users,
            'updateRoute' => 'admin.partners.update',
            'indexRoute' => 'admin.partners.index',
        ]);
    }

    public function update(Request $request, Partners $partner): RedirectResponse
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

        $partner->update($validated);
        
        return redirect()->route('admin.partners.show', $partner->id)
            ->with('success', 'Partner sikeresen frissítve.');
    }

    public function destroy(Partners $partner): RedirectResponse
    {
        $partner->delete();
        
        return redirect()->route('admin.partners.index')
            ->with('success', 'Partner sikeresen törölve.');
    }

    public function addContact(Request $request, $partnerId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'relationship' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $partner = Partners::findOrFail($partnerId);

            PartnerContact::create([
                'partner_id' => $partnerId,
                'name' => $request->name,
                'relationship' => $request->relationship,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
                'is_primary' => false,
            ]);

            $contacts = PartnerContact::where('partner_id', $partnerId)->get();
            $contactsHtml = view('admin.partners._contacts_list', ['contacts' => $contacts])->render();

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen hozzáadva!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Partner contact creation error', [
                'partner_id' => $partnerId,
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
            $contact = PartnerContact::findOrFail($contactId);
            $partnerId = $contact->partner_id;

            $contact->delete();

            $contacts = PartnerContact::where('partner_id', $partnerId)->get();
            $contactsHtml = view('admin.partners._contacts_list', ['contacts' => $contacts])->render();

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen törölve!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Partner contact deletion error', [
                'contact_id' => $contactId,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba a törlés során: '.$e->getMessage(),
            ], 500);
        }
    }

    public function uploadDocument(Request $request, $partnerId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240',
                'description' => 'nullable|string|max:1000',
            ]);

            $partner = Partners::findOrFail($partnerId);
            $file = $request->file('file');

            $filename = time().'_'.$file->getClientOriginalName();
            $path = $file->storeAs('partner-documents', $filename, 'public');

            PartnerDocument::create([
                'partner_id' => $partnerId,
                'name' => $request->input('name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            $documents = PartnerDocument::where('partner_id', $partnerId)->get();
            $documentsHtml = view('admin.partners._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Partner document upload error', [
                'partner_id' => $partnerId,
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
            $document = PartnerDocument::findOrFail($documentId);
            $partnerId = $document->partner_id;

            if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
                Storage::disk('public')->delete($document->file_path);
            }

            $document->delete();

            $documents = PartnerDocument::where('partner_id', $partnerId)->get();
            $documentsHtml = view('admin.partners._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Partner document delete error', [
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

