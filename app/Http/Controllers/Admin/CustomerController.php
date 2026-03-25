<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomersRequest;
use App\Mail\PropertiesForCustomer;
use App\Models\CustomerContact;
use App\Models\CustomerDocument;
use App\Models\CustomerOffer;
use App\Models\CustomerSearch;
use App\Models\Customers;
use App\Models\Property;
use App\Models\PropertyAttribute;
use App\Models\PropertyAttributeCategory;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\SettlementPart;
use App\Models\User;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(): View
    {
        $query = Customers::with('referens')->latest();
        
        if (request()->has('refId')) {
            $query->where('refId', request()->input('refId'));
        }
        
        $customers = $query->paginate(20);
        
        return view('admin.customers.index', [
            'title' => 'Vevők',
            'pageTitle' => 'Vevők',
            'customers' => $customers,
            'indexRoute' => 'admin.customers.index',
            'createRoute' => 'admin.customers.create',
            'editRoute' => 'admin.customers.edit',
            'destroyRoute' => 'admin.customers.destroy',
            'showRoute' => 'admin.customers.show',
        ]);
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        return view('admin.customers.create', [
            'title' => 'Új vevő',
            'pageTitle' => 'Új vevő',
            'users' => $users,
            'storeRoute' => 'admin.customers.store',
            'indexRoute' => 'admin.customers.index',
        ]);
    }

    public function store(CustomersRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        $customer = Customers::create($validated);
        
        // Save search parameters if provided
        $searchParams = $request->get('p');
        if (!empty($searchParams)) {
            CustomerSearch::create([
                'customer_id' => $customer->id,
                'search' => json_encode($searchParams),
            ]);
        }
        
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Vevő sikeresen létrehozva.');
    }

    public function show(Customers $customer): View
    {
        $customer->load(['referens', 'contacts', 'documents']);
        
        $searches = CustomerSearch::where('customer_id', $customer->id)->get();
        
        // Decode searches into arrays
        foreach ($searches as $s) {
            $s->search_array = json_decode($s->search, true) ?: [];
        }
        
        return view('admin.customers.show', [
            'title' => 'Vevő részletei',
            'pageTitle' => 'Vevő: ' . $customer->name_0,
            'customer' => $customer,
            'searches' => $searches,
            'indexRoute' => 'admin.customers.index',
            'editRoute' => 'admin.customers.edit',
        ]);
    }

    public function edit(Customers $customer): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        // Load search parameters if exists
        $search = CustomerSearch::where('customer_id', $customer->id)->first();
        $searchParams = [];
        if ($search) {
            $searchParams = json_decode($search->search, true) ?: [];
        }
        
        // Get property attribute categories for search parameters
        $propAttrsCats = PropertyAttributeCategory::with('propertyAttributes')->get();
        
        return view('admin.customers.edit', [
            'title' => 'Vevő szerkesztése',
            'pageTitle' => 'Vevő szerkesztése',
            'customer' => $customer,
            'users' => $users,
            'searchParams' => $searchParams,
            'propAttrsCats' => $propAttrsCats,
            'propertyTypes' => PropertyType::all()->pluck('name', 'id')->toArray(),
            'propertySubtypes' => PropertySubtype::all()->pluck('name', 'id')->toArray(),
            'settlements' => Settlement::all()->pluck('name', 'id')->toArray(),
            'settlementParts' => SettlementPart::all()->pluck('name', 'id')->toArray(),
            'updateRoute' => 'admin.customers.update',
            'indexRoute' => 'admin.customers.index',
        ]);
    }

    public function update(CustomersRequest $request, Customers $customer): RedirectResponse
    {
        $validated = $request->validated();
        
        $customer->update($validated);
        
        // Save or update search parameters
        $searchParams = $request->get('p');
        if (!empty($searchParams)) {
            $search = CustomerSearch::firstOrNew(['customer_id' => $customer->id]);
            $search->search = json_encode($searchParams);
            $search->save();
        } else {
            CustomerSearch::where('customer_id', $customer->id)->delete();
        }
        
        return redirect()->route('admin.customers.show', $customer->id)
            ->with('success', 'Vevő sikeresen frissítve.');
    }

    public function destroy(Customers $customer): RedirectResponse
    {
        $customer->delete();
        
        return redirect()->route('admin.customers.index')
            ->with('success', 'Vevő sikeresen törölve.');
    }

    /**
     * Execute a saved search and return matching properties (AJAX)
     */
    public function executeSearch(Request $request, $id, $searchId, PropertyService $propertyService): View
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

        // Create a request-like object
        $req = new Request($cleanParams);

        $properties = $propertyService->getPropertiesWithFilters($req, 50);

        return view('admin.customers._properties_list', ['properties' => $properties]);
    }

    /**
     * Send property email to customer manually
     */
    public function sendPropertyEmail(Request $request, $customerId): RedirectResponse
    {
        $customer = Customers::findOrFail($customerId);
        $propertyIdsRaw = $request->input('property_ids', []);
        $propertyIds = is_array($propertyIdsRaw)
            ? $propertyIdsRaw
            : array_filter(array_map('trim', explode(',', (string) $propertyIdsRaw)));

        $properties = Property::whereIn('id', $propertyIds)->get();

        if (empty($to = $customer->email)) {
            return back()->with('error', 'A vevőnek nincs beállítva email cím');
        }

        Mail::to($to)->send(new PropertiesForCustomer($customer, $properties));

        return back()->with('success', 'Email elküldve');
    }

    /**
     * Send offer email to customer
     */
    public function sendOffer(Request $request, $customerId): JsonResponse
    {
        $customer = Customers::findOrFail($customerId);
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
    public function getOffers($customerId, $searchId): View
    {
        $offers = CustomerOffer::where('customer_id', $customerId)
            ->where('customer_search_id', $searchId)
            ->with('customer')
            ->orderBy('sent_at', 'desc')
            ->get();

        return view('admin.customers._offers_list', ['offers' => $offers]);
    }

    /**
     * Get offer details
     */
    public function getOfferDetails($offerId): View
    {
        $offer = CustomerOffer::with(['customer', 'customerSearch'])->findOrFail($offerId);
        $properties = Property::whereIn('id', $offer->property_ids)->get();

        return view('admin.customers._offer_details', [
            'offer' => $offer,
            'properties' => $properties,
        ]);
    }

    /**
     * Add a contact to a customer
     */
    public function addContact(Request $request, $customerId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'required|string|max:100',
                'relationship' => 'nullable|string|max:50',
                'phone' => 'nullable|string|max:100',
                'email' => 'nullable|email|max:100',
                'notes' => 'nullable|string|max:1000',
            ]);

            $customer = Customers::findOrFail($customerId);

            CustomerContact::create([
                'customer_id' => $customerId,
                'name' => $request->name,
                'relationship' => $request->relationship,
                'phone' => $request->phone,
                'email' => $request->email,
                'notes' => $request->notes,
                'is_primary' => false,
            ]);

            $contacts = CustomerContact::where('customer_id', $customerId)->get();
            $contactsHtml = view('admin.customers._contacts_list', ['contacts' => $contacts])->render();

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen hozzáadva!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Customer contact creation error', [
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
    public function deleteContact($contactId): JsonResponse
    {
        try {
            $contact = CustomerContact::findOrFail($contactId);
            $customerId = $contact->customer_id;

            $contact->delete();

            $contacts = CustomerContact::where('customer_id', $customerId)->get();
            $contactsHtml = view('admin.customers._contacts_list', ['contacts' => $contacts])->render();

            return response()->json([
                'success' => true,
                'message' => 'Kapcsolattartó sikeresen törölve!',
                'contacts_html' => $contactsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Customer contact deletion error', [
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
     * Upload a document for a customer
     */
    public function uploadDocument(Request $request, $customerId): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $customer = Customers::findOrFail($customerId);
            $file = $request->file('file');

            // Generate unique filename
            $filename = time().'_'.$file->getClientOriginalName();

            // Store file in customer documents directory
            $path = $file->storeAs('customer-documents', $filename, 'public');

            // Create document record
            $document = CustomerDocument::create([
                'customer_id' => $customerId,
                'name' => $request->input('name') ?: $file->getClientOriginalName(),
                'category' => $request->input('category', 'other'),
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->input('description'),
            ]);

            $documents = CustomerDocument::where('customer_id', $customerId)->get();
            $documentsHtml = view('admin.customers._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve!',
                'documents_html' => $documentsHtml,
            ]);

        } catch (\Exception $e) {
            \Log::error('Customer document upload error', [
                'customer_id' => $customerId,
                'error' => $e->getMessage(),
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
    public function deleteDocument($documentId): JsonResponse
    {
        try {
            $document = CustomerDocument::findOrFail($documentId);
            $customerId = $document->customer_id;

            // Delete the document (this will also delete the file via model event)
            $document->delete();

            $documents = CustomerDocument::where('customer_id', $customerId)->get();
            $documentsHtml = view('admin.customers._documents_list', ['documents' => $documents])->render();

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
}

