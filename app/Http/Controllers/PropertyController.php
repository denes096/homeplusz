<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Services\LabelService;
use App\Services\PropertyService;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function __construct(
        private PropertyService $propertyService,
        private LabelService $labelService,
    ) {

    }

    public function list(Request $request) {
        $labels = $this->labelService->getActiveLabels()->filter( fn($label) => $label->properties_count > 0);

        $propertiesForLabels= [];
        foreach( $labels as $label) {
            $propertiesForLabels[$label->name] = $this->propertyService->getPropertiesWithFiltersForListByLabels($request, $label);
        }

        return view('property.list', compact(
            'propertiesForLabels',
            'labels',
        ));
    }

    public function show(int $id, Request $request)
    {
        $property = $this->propertyService->getById($id);

        return view('property.show', compact('property'));
    }
}
