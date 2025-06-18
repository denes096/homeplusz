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
    ) {

    }

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

        if (!$property) {
            abort(404);
        }

        return view('property.show', compact('property'));
    }

    public function getByCode(string $code, Request $request)
    {
        $property = $this->propertyService->getByCode($code);

        if (!$property) {
            $project = $this->projectService->getByCode($code);

            if (!$project) {
                return back()->with('message', 'Nem található ilyen projekt/ingatlan');
            }
            return view('project.show', compact('project'));
        }

        return view('property.show', compact('property'));
    }
}
