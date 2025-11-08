<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\LabelService;
use App\Services\ProjectService;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(
        private PropertyService $propertyService,
        private LabelService $labelService,
        private ProjectService $projectService,
    ) {}

    //    public function list(Request $request) {
    //        $labels = $this->labelService->getActiveLabels()->filter( fn($label) => $label->properties_count > 0);
    //
    //        $propertiesForLabels= [];
    //        foreach( $labels as $label) {
    //            $propertiesForLabels[$label->name] = $this->propertyService->getPropertiesWithFiltersForListByLabels($request, $label);
    //        }
    //
    //        return view('property.list', compact(
    //            'propertiesForLabels',
    //            'labels',
    //        ));
    //    }

    public function list(Request $request)
    {
        $properties = $this->propertyService->getPropertiesWithFilters($request, 10);

        if ($request->ajax()) {
            return view('includes.property-cards', compact('properties'))->render();
        }

        return view('property.list', compact('properties'));
    }

    public function show(int $id, Request $request)
    {
        $property = $this->propertyService->getById($id);

        if (! $property) {
            abort(404);
        }

        $similarProperties = $this->propertyService->getSimilarProperties($property, 4);

        return view('property.show', compact('property', 'similarProperties'));
    }

    public function getByCode(string $code, Request $request)
    {

        $property = $this->propertyService->getByCode($code);

        if (! $property) {
            $properties = $this->propertyService->getByUserName($code);

            if (! $properties || $properties->count() == 0) {

                $project = $this->projectService->getByCode($code);

                if (! $project) {
                    return back()->with('message', 'Nem található ilyen projekt/ingatlan');
                }

                return view('project.show', compact('project'));
            }

            return view('property.list', compact('properties'));
        }

        $similarProperties = $this->propertyService->getSimilarProperties($property, 4);

        return view('property.show', compact('property', 'similarProperties'));
    }

    public function favorites(Request $request)
    {
        // Check if this is a POST request with JSON content (from fetch)
        if ($request->isMethod('POST') && $request->header('Content-Type') === 'application/json') {
            // Get favorite property IDs from request (passed from JavaScript)
            $favoriteIds = $request->get('favorites', []);

            if (empty($favoriteIds)) {
                $properties = collect();
            } else {
                try {
                    $properties = $this->propertyService->getPropertiesByIds($favoriteIds);
                } catch (\Exception $e) {
                    \Log::error('Error getting properties', ['error' => $e->getMessage()]);
                    $properties = collect();
                }
            }

            return view('includes.property-cards', compact('properties'))->render();
        }

        // For GET requests, return empty properties - the page will show empty state
        // The JavaScript will handle loading favorites from localStorage
        $properties = collect();

        return view('property.favorites', compact('properties'));
    }

    public function searchForDropdown(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $properties = Property::where('property_code', 'LIKE', "%{$query}%")
            ->orWhere('title', 'LIKE', "%{$query}%")
            ->select('id', 'property_code', 'title')
            ->limit(10)
            ->get();

        return response()->json($properties);
    }
}
