<?php

namespace App\Http\Controllers;

use App\Services\LabelService;
use App\Services\ProjectService;
use App\Services\PropertyService;
use App\Services\SettlementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class HomeController extends Controller
{

    public function __construct(
        private LabelService $labelService,
        private PropertyService $propertyService,
        private SettlementService $settlementService,
        private ProjectService $projectService,
    )
    {
    }

    public function searchBySettlementGroup($settlementGroupId): View{

        $settlementGroups = $this->settlementService->getSettlementGroups();

        $settlementGroup = $this->settlementService->getSettlementGroupById($settlementGroupId);

        $propertiesInTheArea = $this->propertyService->getPropertiesForListingBySettlements($settlementGroup->settlements()->get());

        return view('properties-in-settlement-group', [
            'settlementGroup' => $settlementGroup,
            'settlementGroups' => $settlementGroups,
            'propertiesInTheArea' => $propertiesInTheArea,
        ]);
    }

    public function index(): View {
        $labels = $this->labelService->getActiveLabels()->filter( fn($label) => $label->properties_count > 0);
        $settlementGroups = $this->settlementService->getSettlementGroups();
        $featuredProperties = $this->propertyService->getFeaturedProperties(5);

        $propertiesForLabels= [];
        foreach( $labels as $label) {
            $propertiesForLabels[$label->name] = $this->propertyService->getPropertiesForListingByLabel($label, 5);
        }

        return view('welcome', [
            'labels' => $labels,
            'featuredProperties' => $featuredProperties,
            'propertiesForLabels' => $propertiesForLabels,
            'settlementGroups' => $settlementGroups,
            'projects' => $this->projectService->getAll(),
        ]);
    }
}
