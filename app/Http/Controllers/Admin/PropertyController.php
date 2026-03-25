<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\PropertyRequest;
use App\Models\Label;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyAttribute;
use App\Models\PropertyDocument;
use App\Models\PropertySubtype;
use App\Models\PropertyType;
use App\Models\Settlement;
use App\Models\SettlementPart;
use App\Models\UniqueCode;
use App\Models\User;
use App\Services\PropertyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PropertyController extends Controller
{
    public function index(Request $request): View
    {
        $query = Property::with(['settlement', 'settlementPart', 'propertyType', 'user', 'client']);

        // Filter by active status
        if ($request->has('filter')) {
            switch ($request->filter) {
                case 'active':
                    $query->where('is_active', true);
                    break;
                case 'inactive':
                    $query->where('is_active', false);
                    break;
                case 'own':
                    $query->where('user_id', auth()->id());
                    break;
            }
        }

        // Filter by user_id if provided
        if ($request->has('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Search functionality
        if ($request->has('search')) {
            $searchTerm = $request->search;
            $query->where(function ($q) use ($searchTerm) {
                $q->where('property_code', 'like', '%' . $searchTerm . '%')
                    ->orWhere('title', 'like', '%' . $searchTerm . '%')
                    ->orWhereHas('user', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhereHas('settlement', function ($q) use ($searchTerm) {
                        $q->where('name', 'like', '%' . $searchTerm . '%');
                    });
            });
        }

        $properties = $query->latest()->paginate(20)->withQueryString();

        return view('admin.properties.index', [
            'title' => 'Ingatlanok',
            'pageTitle' => 'Ingatlanok',
            'properties' => $properties,
            'currentFilter' => $request->get('filter', 'all'),
            'searchTerm' => $request->get('search', ''),
        ]);
    }

    public function create(): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $settlements = Settlement::orderBy('name')->get()->pluck('fullName', 'id')->toArray();
        $settlementParts = SettlementPart::with('settlement')->orderBy('name')->get();
        $propertyTypes = PropertyType::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $propertySubtypes = PropertySubtype::with('propertyType')->orderBy('name')->get();
        $projects = Project::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $clients = \App\Models\Client::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $labels = Label::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $attributes = PropertyAttribute::with('category')->orderBy('label')->get();
        
        // Group attributes by category
        $attributesByCategory = $attributes->groupBy(function ($attribute) {
            return $attribute->category->name ?? 'Egyéb';
        });

        return view('admin.properties.create', [
            'title' => 'Új ingatlan',
            'pageTitle' => 'Új ingatlan',
            'users' => $users,
            'settlements' => $settlements,
            'settlementParts' => $settlementParts,
            'propertyTypes' => $propertyTypes,
            'propertySubtypes' => $propertySubtypes,
            'projects' => $projects,
            'clients' => $clients,
            'labels' => $labels,
            'attributesByCategory' => $attributesByCategory,
            'attributes' => $attributes,
            'storeRoute' => 'admin.properties.store',
            'indexRoute' => 'admin.properties.index',
            'defaultPropertyCode' => UniqueCode::getNextCode(),
        ]);
    }

    public function store(PropertyRequest $request): RedirectResponse
    {
        try {
            $validated = $request->validated();
            
            // Set user_id if not provided
            if (!isset($validated['user_id'])) {
                $validated['user_id'] = auth()->id();
            }

            // Handle images
            if ($request->hasFile('images')) {
                $imagePaths = [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/temp', 'public');
                    $imagePaths[] = $path;
                }
                $validated['images'] = json_encode($imagePaths);
            } else {
                $validated['images'] = json_encode([]);
            }

            // Create property
            $property = Property::create($validated);

            // Update unique code
            UniqueCode::updateCode((int) explode('/', $validated['property_code'])[0]);

            // Handle labels
            if ($request->has('labels')) {
                $property->labels()->sync($request->labels);
            }

            // Handle property attributes
            if ($request->has('properties')) {
                foreach ($request->properties as $attributeId => $value) {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                    $property->attributes()->syncWithoutDetaching([
                        $attributeId => ['value' => $value],
                    ]);
                }
            }

            // Move images from temp to property directory
            if ($property->images) {
                $images = json_decode($property->images, true);
                $finalPaths = [];
                foreach ($images as $imgPath) {
                    if (Storage::disk('public')->exists($imgPath)) {
                        $newPath = 'uploads/' . $property->id . '/' . basename($imgPath);
                        Storage::disk('public')->makeDirectory('uploads/' . $property->id);
                        Storage::disk('public')->move($imgPath, $newPath);
                        $finalPaths[] = $newPath;
                    }
                }
                $property->images = json_encode($finalPaths);
                $property->save();
            }

            return redirect()->route('admin.properties.show', $property->id)
                ->with('success', 'Ingatlan sikeresen létrehozva.');
        } catch (\Exception $e) {
            \Log::error('Property creation error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hiba történt az ingatlan létrehozása során: ' . $e->getMessage());
        }
    }

    public function show(Property $property): View
    {
        $property->load([
            'settlement',
            'settlementPart',
            'propertyType',
            'propertySubtype',
            'project',
            'client',
            'labels',
            'attributes.category',
            'user',
            'documents',
        ]);

        $propertyService = new PropertyService();
        $matchingSearches = $propertyService->findMatchingCustomerSearches($property);

        return view('admin.properties.show', [
            'title' => 'Ingatlan részletei',
            'pageTitle' => 'Ingatlan: ' . $property->title,
            'property' => $property,
            'matchingSearches' => $matchingSearches,
            'indexRoute' => 'admin.properties.index',
            'editRoute' => 'admin.properties.edit',
        ]);
    }

    public function edit(Property $property): View
    {
        $users = User::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $settlements = Settlement::orderBy('name')->get()->pluck('fullName', 'id')->toArray();
        $settlementParts = SettlementPart::with('settlement')->orderBy('name')->get();
        $propertyTypes = PropertyType::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $propertySubtypes = PropertySubtype::with('propertyType')->orderBy('name')->get();
        $projects = Project::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $clients = \App\Models\Client::orderBy('name')->get()->pluck('name', 'id')->toArray();
        $labels = Label::orderBy('name')->get()->pluck('name', 'id')->toArray();
        
        // Load property attributes with their current values
        $property->load('attributes.category');
        $attributes = PropertyAttribute::with('category')->orderBy('label')->get();
        
        // Group attributes by category
        $attributesByCategory = $attributes->groupBy(function ($attribute) {
            return $attribute->category->name ?? 'Egyéb';
        });

        // Get current attribute values
        $currentAttributeValues = [];
        foreach ($property->attributes as $attr) {
            $currentAttributeValues[$attr->id] = $attr->pivot->value;
        }

        return view('admin.properties.edit', [
            'title' => 'Ingatlan szerkesztése',
            'pageTitle' => 'Ingatlan szerkesztése',
            'property' => $property,
            'users' => $users,
            'settlements' => $settlements,
            'settlementParts' => $settlementParts,
            'propertyTypes' => $propertyTypes,
            'propertySubtypes' => $propertySubtypes,
            'projects' => $projects,
            'clients' => $clients,
            'labels' => $labels,
            'attributesByCategory' => $attributesByCategory,
            'attributes' => $attributes,
            'currentAttributeValues' => $currentAttributeValues,
            'updateRoute' => 'admin.properties.update',
            'indexRoute' => 'admin.properties.index',
        ]);
    }

    public function update(PropertyRequest $request, Property $property): RedirectResponse
    {
        try {
            $validated = $request->validated();

            // Handle images
            if ($request->hasFile('images')) {
                $imagePaths = json_decode($property->images, true) ?? [];
                foreach ($request->file('images') as $image) {
                    $path = $image->store('uploads/' . $property->id, 'public');
                    $imagePaths[] = $path;
                }
                $validated['images'] = json_encode($imagePaths);
            } else {
                // Keep existing images if no new ones uploaded
                $validated['images'] = $property->images;
            }

            // Update property
            $property->update($validated);

            // Update unique code
            UniqueCode::updateCode((int) explode('/', $validated['property_code'])[0]);

            // Handle labels
            if ($request->has('labels')) {
                $property->labels()->sync($request->labels);
            } else {
                $property->labels()->detach();
            }

            // Handle property attributes
            if ($request->has('properties')) {
                foreach ($request->properties as $attributeId => $value) {
                    if (is_array($value)) {
                        $value = json_encode($value);
                    }
                    $property->attributes()->syncWithoutDetaching([
                        $attributeId => ['value' => $value],
                    ]);
                }
            }

            return redirect()->route('admin.properties.show', $property->id)
                ->with('success', 'Ingatlan sikeresen frissítve.');
        } catch (\Exception $e) {
            \Log::error('Property update error: ' . $e->getMessage(), [
                'exception' => $e,
                'request_data' => $request->all(),
            ]);

            return redirect()->back()
                ->withInput()
                ->with('error', 'Hiba történt az ingatlan frissítése során: ' . $e->getMessage());
        }
    }

    public function destroy(Property $property): RedirectResponse
    {
        try {
            // Delete associated images
            if ($property->images) {
                $images = json_decode($property->images, true);
                foreach ($images as $imgPath) {
                    if (Storage::disk('public')->exists($imgPath)) {
                        Storage::disk('public')->delete($imgPath);
                    }
                }
            }

            // Delete property directory
            if (Storage::disk('public')->exists('uploads/' . $property->id)) {
                Storage::disk('public')->deleteDirectory('uploads/' . $property->id);
            }

            $property->delete();

            return redirect()->route('admin.properties.index')
                ->with('success', 'Ingatlan sikeresen törölve.');
        } catch (\Exception $e) {
            \Log::error('Property deletion error: ' . $e->getMessage());

            return redirect()->back()
                ->with('error', 'Hiba történt a törlés során: ' . $e->getMessage());
        }
    }

    public function toggleActive(Property $property): JsonResponse
    {
        try {
            $newState = $property->toggleActive();

            return response()->json([
                'success' => true,
                'is_active' => $newState,
                'message' => $newState ? 'Ingatlan aktiválva' : 'Ingatlan deaktiválva',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Hiba történt: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function showMatchingSearches(Property $property): View
    {
        $propertyService = new PropertyService();
        $matches = $propertyService->findMatchingCustomerSearches($property);

        return view('admin.properties.matching-searches', [
            'title' => 'Illeszkedő keresések',
            'pageTitle' => 'Illeszkedő keresések: ' . $property->title,
            'property' => $property,
            'matches' => $matches,
            'indexRoute' => 'admin.properties.index',
        ]);
    }

    public function sendToMatchingSearch(Request $request, Property $property, $searchId): JsonResponse
    {
        try {
            $search = \App\Models\CustomerSearch::findOrFail($searchId);

            // Send email
            \Mail::to($search->customer->email)->send(new \App\Mail\PropertiesForCustomer($search->customer, collect([$property])));

            // Save offer record
            \App\Models\CustomerOffer::create([
                'customer_id' => $search->customer_id,
                'customer_search_id' => $searchId,
                'property_ids' => [$property->id],
                'email_subject' => 'Ingatlan ajánlat - ' . $search->customer->name_0,
                'email_content' => 'Kedves ' . $search->customer->name_0 . '! Küldjük Önnek a keresési paramétereinek megfelelő ingatlan ajánlatot.',
                'sent_at' => now(),
            ]);

            return response()->json(['success' => true, 'message' => 'Ajánlat sikeresen elküldve']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Hiba történt az email küldése során: ' . $e->getMessage()], 500);
        }
    }

    public function uploadDocument(Request $request, Property $property): JsonResponse
    {
        try {
            $request->validate([
                'name' => 'nullable|string|max:255',
                'category' => 'nullable|in:contract,order,purchase,inspection,other',
                'file' => 'required|file|max:10240', // 10MB max
                'description' => 'nullable|string|max:1000',
            ]);

            $file = $request->file('file');

            // Generate unique filename
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('property-documents/' . $property->id, $filename, 'public');

            // Create document record
            $document = PropertyDocument::create([
                'property_id' => $property->id,
                'name' => $request->name ?: $file->getClientOriginalName(),
                'category' => $request->category ?: 'other',
                'file_path' => $path,
                'original_name' => $file->getClientOriginalName(),
                'file_type' => $file->getMimeType(),
                'file_size' => $file->getSize(),
                'description' => $request->description,
            ]);

            $documents = PropertyDocument::where('property_id', $property->id)->get();
            $documentsHtml = view('admin.properties._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen feltöltve',
                'documents_html' => $documentsHtml,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validációs hiba',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Document upload error: ' . $e->getMessage(), [
                'exception' => $e,
                'property_id' => $property->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a feltöltés során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function deleteDocument(Request $request, Property $property, $documentId): JsonResponse
    {
        try {
            $document = PropertyDocument::where('property_id', $property->id)
                ->where('id', $documentId)
                ->firstOrFail();

            $document->delete(); // This will also delete the file due to the model's boot method

            $documents = PropertyDocument::where('property_id', $property->id)->get();
            $documentsHtml = view('admin.properties._documents_list', ['documents' => $documents])->render();

            return response()->json([
                'success' => true,
                'message' => 'Dokumentum sikeresen törölve',
                'documents_html' => $documentsHtml,
            ]);
        } catch (\Exception $e) {
            \Log::error('Document deletion error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Hiba történt a törlés során: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function findPropertyOrProject(string $unique_id): RedirectResponse
    {
        $property = Property::where('property_code', $unique_id)->first();

        if (!$property) {
            $project = Project::where('project_code', $unique_id)->first();
            if ($project) {
                return redirect()->route('admin.projects.edit', $project->id);
            }
            return redirect()->route('admin.properties.index')
                ->with('error', 'Nem található ingatlan vagy projekt ezzel a kóddal.');
        }

        return redirect()->route('admin.properties.show', $property->id);
    }
}

