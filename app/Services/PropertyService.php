<?php

namespace App\Services;

use App\Models\Label;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class PropertyService
{
    public function getById(int $id)
    {
        $propertyQuery = Property::where('id', '=', $id)
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }]);

        return $propertyQuery->first();
    }

    public function getRandomPropertyList(int $limit = 15)
    {
        return Property::inRandomOrder()->limit($limit)->get();
    }

    public function getByCode(string $code)
    {
        $propertyQuery = Property::where('property_code', '=', $code)
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }]);

        return $propertyQuery->first();
    }

    public function getPropertiesForListingByLabel(Label $label, ?int $limit = null): Collection
    {

        $propertyQuery = Property::whereHas('labels', function ($query) use ($label) {
            $query->where('name', $label->name);
        })
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }]);

        if (! empty($limit)) {
            $propertyQuery->limit($limit);
        }

        return $propertyQuery->get();
    }

    public function getPropertiesForListingBySettlements(Collection $settlements, ?int $limit = null)
    {
        $settlementIds = $settlements->pluck('id');
        $propertyQuery = Property::whereIn('settlement_id', $settlementIds->toArray())
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }]);
        if (! empty($limit)) {
            $propertyQuery->limit($limit);
        }

        return $propertyQuery->get();

    }

    public function getFeaturedProperties(?int $limit = null): Collection
    {
        $propertyQuery = Property::where('featured', 1)
            ->with('labels')
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }])
            ->where('is_active', 1);
        if (! empty($limit)) {
            $propertyQuery->limit($limit);
        }

        return $propertyQuery->get();
    }

    public function getPropertiesWithFilters(Request $request, int $limit = 15)
    {
        $query = Property::query()
            ->with('labels')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }])
            ->where('is_active', 1);

        if ($request->filled('ad_type')) {
            $query->where('ad_type', $request->input('ad_type'));
        }

        // 2. Települések (több is lehet)
        if ($request->filled('settlements')) {
            $query->whereHas('settlement', function ($query) use ($request) {
                $query->whereIn('id', $request->input('settlements'));
            });
        } else {
            $query->with('settlement');
        }

        if ($request->filled('newly_built')) {
            $query->whereHas('attributes', function ($query) {
                $query->where('name', 'epulet_allapot_belul')->whereRaw(
                    'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
                    ['NEWLY_BUILT']
                );
            });
        }

        // 3. Ingatlantípusok (több is lehet)
        if ($request->filled('property_types')) {
            $query->whereIn('property_type_id', $request->input('property_types'));
        }

        // 4. Település részek (több is lehet)
        if ($request->filled('settlement_parts')) {
            $query->whereIn('settlement_part_id', $request->input('settlement_parts'));
        }

        if ($request->filled('number_of_rooms_min') || $request->filled('number_of_rooms_max')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('name', 'epulet_szobaszam');

                if ($request->filled('number_of_rooms_min')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
                        [$request->input('number_of_rooms_min')]
                    );
                }

                if ($request->filled('number_of_rooms_max')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) <= ?',
                        [$request->input('number_of_rooms_max')]
                    );
                }
            });
        }

        // 5. Méret intervallum
        if ($request->filled('property_area_min') || $request->filled('property_area_max')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('name', 'epulet_lakotermeret');

                if ($request->filled('property_area_min')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
                        [$request->input('property_area_min')]
                    );
                }

                if ($request->filled('property_area_max')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) <= ?',
                        [$request->input('property_area_max')]
                    );
                }
            });
        }

        if ($request->filled('price_min')) {
            if ($request->input('ad_type') == 'sell') {
                $query->where('price', '>=', $request->input('price_min') * 1000000);
            } else {
                $query->where('rental_price', '>=', $request->input('price_min') * 1000);
            }
        }
        if ($request->filled('price_max')) {
            if ($request->input('ad_type') == 'sell') {
                $query->where('price', '<=', $request->input('price_max') * 1000000);
            } else {
                $query->where('rental_price', '<=', $request->input('price_max') * 1000);
            }
        }

        // Dynamic property attributes filtering
        $this->applyDynamicAttributeFilters($query, $request);

        return $query->paginate($limit);
    }

    public function getPropertiesWithFiltersForListByLabels(Request $request, Label $label, ?int $limit = null)
    {
        $query = Property::query()
            ->whereHas('labels', function ($query) use ($label) {
                $query->where('name', $label->name);
            })
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }])
            ->where('ad_type', $request->input('ad_type'));

        // 2. Települések (több is lehet)
        if ($request->filled('settlements')) {
            $query->whereHas('settlement', function ($query) use ($request) {
                $query->whereIn('id', $request->input('settlements'));
            });
        } else {
            $query->with('settlement');
        }

        if ($request->filled('newly_built')) {
            $query->whereHas('attributes', function ($query) {
                $query->where('name', 'epulet_allapot_belul')->whereRaw(
                    'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
                    ['NEWLY_BUILT']
                );
            });
        }

        // 3. Ingatlantípusok (több is lehet)
        if ($request->filled('property_types')) {
            $query->whereIn('property_type_id', $request->input('property_types'));
        }

        if ($request->filled('number_of_rooms_min') || $request->filled('number_of_rooms_max')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('name', 'epulet_szobaszam');

                if ($request->filled('number_of_rooms_min')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
                        [$request->input('number_of_rooms_min')]
                    );
                }

                if ($request->filled('number_of_rooms_max')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) <= ?',
                        [$request->input('number_of_rooms_max')]
                    );
                }
            });
        }

        // 5. Méret intervallum
        if ($request->filled('property_area_min') || $request->filled('property_area_max')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('name', 'epulet_lakotermeret');

                if ($request->filled('property_area_min')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
                        [$request->input('property_area_min')]
                    );
                }

                if ($request->filled('property_area_max')) {
                    $q->whereRaw(
                        'CAST(property_property_attribute.value AS UNSIGNED) <= ?',
                        [$request->input('property_area_max')]
                    );
                }
            });
        }
        if ($request->filled('price_min')) {
            if ($request->input('ad_type') == 'sell') {
                $query->where('price', '>=', $request->input('price_min') * 1000000);
            } else {
                $query->where('rental_price', '>=', $request->input('price_min') * 1000);
            }
        }
        if ($request->filled('price_max')) {
            if ($request->input('ad_type') == 'sell') {
                $query->where('price', '<=', $request->input('price_max') * 1000000);
            } else {
                $query->where('rental_price', '<=', $request->input('price_max') * 1000);
            }
        }

        return $query->paginate(15);
    }

    public function getByProjectId(int $projectId, ?int $limit = null): Collection
    {
        return Property::where('project_id', $projectId)->get();
    }

    public function getPropertiesByIds(array $ids): Collection
    {
        try {
            return Property::whereIn('id', $ids)
                ->with('settlement')
                ->with('settlementPart')
                ->with('propertyType')
                ->with('propertySubtype')
                ->with(['attributes' => function ($query) {
                    $query->where('show_in_list', true);
                }])
                ->where('is_active', 1)
                ->get();
        } catch (\Exception $e) {
            \Log::error('Error in getPropertiesByIds', ['error' => $e->getMessage(), 'ids' => $ids]);

            return collect();
        }
    }

    public function getSimilarProperties(Property $property, int $limit = 4): Collection
    {
        // Get current property attributes
        $currentSize = $this->getAttributeValue($property, 'epulet_lakotermeret');
        $currentRooms = $this->getAttributeValue($property, 'epulet_szobaszam');
        $currentCondition = $this->getAttributeValue($property, 'epulet_allapot_belul');
        $currentLandSize = $this->getAttributeValue($property, 'area');

        $query = Property::query()
            ->where('id', '!=', $property->id) // Exclude current property
            ->where('is_active', 1)
            ->where('ad_type', $property->ad_type) // Same ad type (sell/rent)
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list', true);
            }]);

        // Apply size filter (±20m2)
        if ($currentSize) {
            $query->whereHas('attributes', function ($q) use ($currentSize) {
                $q->where('name', 'epulet_lakotermeret')
                    ->whereRaw('CAST(property_property_attribute.value AS UNSIGNED) BETWEEN ? AND ?',
                        [$currentSize - 20, $currentSize + 20]);
            });
        }

        // Apply rooms filter (±1)
        if ($currentRooms) {
            $query->whereHas('attributes', function ($q) use ($currentRooms) {
                $q->where('name', 'epulet_szobaszam')
                    ->whereRaw('CAST(property_property_attribute.value AS UNSIGNED) BETWEEN ? AND ?',
                        [$currentRooms - 1, $currentRooms + 1]);
            });
        }

        // Apply condition filter (±1)
        if ($currentCondition) {
            $query->whereHas('attributes', function ($q) use ($currentCondition) {
                $q->where('name', 'epulet_allapot_belul')
                    ->whereRaw('CAST(property_property_attribute.value AS UNSIGNED) BETWEEN ? AND ?',
                        [$currentCondition - 1, $currentCondition + 1]);
            });
        }

        // Apply land size filter (±100m2)
        if ($currentLandSize) {
            $query->whereHas('attributes', function ($q) use ($currentLandSize) {
                $q->where('name', 'epulet_lakotermeret')
                    ->whereRaw('CAST(property_property_attribute.value AS UNSIGNED) BETWEEN ? AND ?',
                        [$currentLandSize - 100, $currentLandSize + 100]);
            });
        }

        return $query->limit($limit)->get();
    }

    private function getAttributeValue(Property $property, string $attributeName): ?int
    {
        $attribute = $property->attributes->firstWhere('name', $attributeName);

        return $attribute ? (int) $attribute->pivot->value : null;
    }

    private function applyDynamicAttributeFilters($query, Request $request)
    {
        $propAttrsCats = \App\Models\PropertyAttributeCategory::with('propertyAttributes')->get();

        $filters = [];

        foreach ($propAttrsCats as $propAttrCat) {
            foreach ($propAttrCat->propertyAttributes as $propAttr) {
                if ($propAttr->type === 'number') {
                    $minField = $propAttr->name.'_min';
                    $maxField = $propAttr->name.'_max';

                    if ($request->filled($minField)) {
                        $filters[] = [
                            'name' => $propAttr->name,
                            'operator' => '>=',
                            'value' => $request->input($minField),
                        ];
                    }
                    if ($request->filled($maxField)) {
                        $filters[] = [
                            'name' => $propAttr->name,
                            'operator' => '<=',
                            'value' => $request->input($maxField),
                        ];
                    }
                } elseif (in_array($propAttr->type, ['select', 'select_multiple'])) {
                    if ($request->filled($propAttr->name)) {
                        $filters[] = [
                            'name' => $propAttr->name,
                            'operator' => 'IN',
                            'value' => $request->input($propAttr->name),
                        ];
                    }
                } elseif ($propAttr->type === 'checkbox') {
                    if ($request->filled($propAttr->name) && $request->get($propAttr->name) == 1) {
                        $filters[] = [
                            'name' => $propAttr->name,
                            'operator' => '=',
                            'value' => 1,
                        ];
                    }
                }
            }
        }

        if (! empty($filters)) {
            $query->whereIn('id', function ($sub) use ($filters) {
                $sub->select('ppa.property_id')
                    ->from('property_property_attribute as ppa')
                    ->join('property_attributes as pa', 'pa.id', '=', 'ppa.property_attribute_id')
                    ->where(function ($q) use ($filters) {
                        foreach ($filters as $f) {
                            if ($f['operator'] === 'IN') {
                                $q->orWhere(function ($q2) use ($f) {
                                    $q2->where('pa.name', $f['name'])
                                        ->whereIn('ppa.value', (array) $f['value']);
                                });
                            } else {
                                $q->orWhere(function ($q2) use ($f) {
                                    $q2->where('pa.name', $f['name'])
                                        ->whereRaw('CAST(ppa.value AS UNSIGNED) '.$f['operator'].' ?', [$f['value']]);
                                });
                            }
                        }
                    })
                    ->groupBy('ppa.property_id')
                    ->havingRaw('COUNT(DISTINCT pa.name) >= ?', [count($filters)]);
            });
        }
    }

    //    private function applyDynamicAttributeFilters($query, Request $request)
    //    {
    //        // Get all property attribute categories and their attributes
    //        $propAttrsCats = \App\Models\PropertyAttributeCategory::with('propertyAttributes')->get();
    //
    //        foreach ($propAttrsCats as $propAttrCat) {
    //            $propAttrs = $propAttrCat->propertyAttributes;
    //
    //            foreach ($propAttrs as $propAttr) {
    //                if ($propAttr->type == 'number') {
    //                    // Handle number range filters
    //                    $minField = $propAttr->name.'_min';
    //                    $maxField = $propAttr->name.'_max';
    //
    //                    if ($request->filled($minField) || $request->filled($maxField)) {
    //                        $query->whereHas('attributes', function ($q) use ($request, $propAttr, $minField, $maxField) {
    //                            $q->where('name', $propAttr->name);
    //
    //                            if ($request->filled($minField)) {
    //                                $q->whereRaw(
    //                                    'CAST(property_property_attribute.value AS UNSIGNED) >= ?',
    //                                    [$request->input($minField)]
    //                                );
    //                            }
    //
    //                            if ($request->filled($maxField)) {
    //                                $q->whereRaw(
    //                                    'CAST(property_property_attribute.value AS UNSIGNED) <= ?',
    //                                    [$request->input($maxField)]
    //                                );
    //                            }
    //                        });
    //                    }
    //                } elseif ($propAttr->type == 'select' || $propAttr->type == 'select_multiple') {
    //                    // Handle select/multiselect filters
    //                    $fieldName = $propAttr->name.'[]';
    //
    //                    if ($request->filled($fieldName)) {
    //                        $query->whereHas('attributes', function ($q) use ($request, $propAttr, $fieldName) {
    //                            $q->where('name', $propAttr->name)
    //                                ->whereIn('value', $request->input($fieldName));
    //                        });
    //                    }
    //                } elseif ($propAttr->type == 'checkbox') {
    //                    // Handle checkbox filters
    //                    if ($request->filled($propAttr->name)) {
    //                        $query->whereHas('attributes', function ($q) use ($propAttr) {
    //                            $q->where('name', $propAttr->name)
    //                                ->where('value', '1');
    //                        });
    //                    }
    //                }
    //            }
    //        }
    //    }

    /**
     * Find matching customer searches for a property
     */
    public function findMatchingCustomerSearches(Property $property): array
    {
        $matches = [];
        $customerSearches = \App\Models\CustomerSearch::with('customer')->get();

        foreach ($customerSearches as $search) {
            $searchParams = json_decode($search->search, true);

            if ($this->propertyMatchesSearch($property, $searchParams)) {
                $matches[] = [
                    'search' => $search,
                    'customer' => $search->customer,
                    'match_score' => $this->calculateMatchScore($property, $searchParams),
                ];
            }
        }

        // Sort by match score (highest first)
        usort($matches, function ($a, $b) {
            return $b['match_score'] <=> $a['match_score'];
        });

        return $matches;
    }

    /**
     * Check if property matches search parameters
     */
    private function propertyMatchesSearch(Property $property, array $searchParams): bool
    {

        if ($searchParams['ad_type'] == 'Eladó') {
            $searchParams['ad_type'] = 'sell';
        } else {
            $searchParams['ad_type'] = 'rent';
        }
        // Ad type check
        if (isset($searchParams['ad_type']) && $property->ad_type !== $searchParams['ad_type']) {
            return false;
        }
        // Settlement check
        if (isset($searchParams['settlements']) && is_array($searchParams['settlements'])) {
            if (! in_array($property->settlement_id, $searchParams['settlements'])) {
                return false;
            }

        }

        // Settlement parts check
        if (isset($searchParams['settlement_parts']) && is_array($searchParams['settlement_parts'])) {
            if (! in_array($property->settlement_part_id, $searchParams['settlement_parts'])) {
                return false;
            }
        }

        // Property type check
        if (isset($searchParams['property_types']) && is_array($searchParams['property_types'])) {
            if (! in_array($property->property_type_id, $searchParams['property_types'])) {
                return false;
            }
        }

        // Property subtype check
        if (isset($searchParams['property_subtypes']) && is_array($searchParams['property_subtypes'])) {
            if (! in_array($property->property_subtype_id, $searchParams['property_subtypes'])) {
                return false;
            }
        }

        // Price range check
        if (isset($searchParams['price_max'])) {
            if ($property->ad_type == 'sell') {
                if ($property->price > $searchParams['price_max']) {

                    return false;
                }
            } else {
                if ($property->rental_price > $searchParams['price_max']) {
                    return false;
                }
            }
        }

        if (isset($searchParams['price_min'])) {
            if ($property->ad_type == 'sell') {
                if ($property->price < $searchParams['price_min']) {
                    return false;
                }
            } else {
                if ($property->rental_price < $searchParams['price_min']) {
                    return false;
                }
            }
        }

        // Newly built check
        if (isset($searchParams['newly_built']) && $searchParams['newly_built'] == 1) {
            $newlyBuiltValue = $this->getPropertyAttributeValue($property, 'epulet_allapot_belul');
            if ($newlyBuiltValue === null || $newlyBuiltValue < 4) { // NEWLY_BUILT = 4
                return false;
            }
        }

        // Number of rooms check
        if (isset($searchParams['number_of_rooms_min']) && $searchParams['number_of_rooms_min'] != 0) {
            $rooms = $this->getPropertyAttributeValue($property, 'epulet_szobaszam');
            if ($rooms === null || $rooms < $searchParams['number_of_rooms_min']) {
                return false;
            }
        }
        if (isset($searchParams['number_of_rooms_max']) && $searchParams['number_of_rooms_max'] != 0) {
            $rooms = $this->getPropertyAttributeValue($property, 'epulet_szobaszam');
            if ($rooms === null || $rooms > $searchParams['number_of_rooms_max']) {
                return false;
            }
        }

        // Property area check
        if (isset($searchParams['property_area_min']) && $searchParams['property_area_min'] != 0) {
            $area = $this->getPropertyAttributeValue($property, 'epulet_lakotermeret');
            if ($area === null || $area < $searchParams['property_area_min']) {
                return false;
            }
        }
        if (isset($searchParams['property_area_max']) && $searchParams['property_area_max'] != 0) {
            $area = $this->getPropertyAttributeValue($property, 'epulet_lakotermeret');
            if ($area === null || $area > $searchParams['property_area_max']) {
                return false;
            }
        }
        // Dynamic attribute filters
        if (! $this->propertyMatchesDynamicAttributes($property, $searchParams)) {
            return false;
        }

        return true;
    }

    /**
     * Check if property matches dynamic attribute filters
     */
    private function propertyMatchesDynamicAttributes(Property $property, array $searchParams): bool
    {
        $propAttrsCats = \App\Models\PropertyAttributeCategory::with('propertyAttributes')->get();

        foreach ($propAttrsCats as $propAttrCat) {
            foreach ($propAttrCat->propertyAttributes as $propAttr) {
                $paramKey = $propAttr->name;

                if ($propAttr->type === 'number') {
                    $minField = $propAttr->name.'_min';
                    $maxField = $propAttr->name.'_max';

                    if (isset($searchParams[$minField]) && $searchParams[$minField] != 0) {
                        $value = $this->getPropertyAttributeValue($property, $propAttr->name);
                        if ($value === null || $value < $searchParams[$minField]) {
                            return false;
                        }
                    }
                    if (isset($searchParams[$maxField]) && $searchParams[$maxField] != 0) {
                        $value = $this->getPropertyAttributeValue($property, $propAttr->name);
                        if ($value === null || $value > $searchParams[$maxField]) {
                            return false;
                        }
                    }
                } elseif (in_array($propAttr->type, ['select', 'select_multiple'])) {
                    if (isset($searchParams[$paramKey])) {
                        $value = $this->getPropertyAttributeValue($property, $propAttr->name);
                        $searchValues = is_array($searchParams[$paramKey]) ? $searchParams[$paramKey] : [$searchParams[$paramKey]];

                        if ($propAttr->type === 'select_multiple') {
                            // For select_multiple, the value is stored as JSON
                            $propertyValues = is_string($value) ? json_decode($value, true) : $value;
                            if (! is_array($propertyValues) || empty(array_intersect($propertyValues, $searchValues))) {
                                return false;
                            }
                        } else {
                            // For single select
                            if ($value === null || ! in_array($value, $searchValues)) {
                                return false;
                            }
                        }
                    }
                } elseif ($propAttr->type === 'checkbox') {
                    if (isset($searchParams[$paramKey]) && $searchParams[$paramKey] == 1) {
                        $value = $this->getPropertyAttributeValue($property, $propAttr->name);
                        dd($value, $searchParams[$paramKey], $paramKey, $propAttr->name);
                        if ($value === null || $value != 1) {
                            return false;
                        }
                    }
                }
            }
        }

        return true;
    }

    /**
     * Calculate match score for property and search parameters
     */
    private function calculateMatchScore(Property $property, array $searchParams): int
    {
        $score = 0;

        // Base score for matching
        $score += 10;

        // Price range bonus
        if (isset($searchParams['price_min']) && isset($searchParams['price_max'])) {
            $priceRange = $searchParams['price_max'] - $searchParams['price_min'];
            $pricePosition = ($property->price - $searchParams['price_min']) / $priceRange;

            // Bonus for being in the middle of the price range
            if ($pricePosition >= 0.3 && $pricePosition <= 0.7) {
                $score += 5;
            }
        }

        // Property type match bonus
        if (isset($searchParams['property_types']) && is_array($searchParams['property_types'])) {
            $score += 3;
        }

        // Settlement match bonus
        if (isset($searchParams['settlements']) && is_array($searchParams['settlements'])) {
            $score += 2;
        }

        // Settlement part match bonus
        if (isset($searchParams['settlement_parts']) && is_array($searchParams['settlement_parts'])) {
            $score += 2;
        }

        // Ad type match bonus
        if (isset($searchParams['ad_type'])) {
            $score += 1;
        }

        // Property subtype match bonus
        if (isset($searchParams['property_subtypes']) && is_array($searchParams['property_subtypes'])) {
            $score += 1;
        }

        return $score;
    }

    /**
     * Get property attribute value by attribute name
     */
    private function getPropertyAttributeValue(Property $property, string $attributeName): ?string
    {
        $attribute = $property->attributes()->where('name', $attributeName)->first();

        dd($attribute->pivot, $attributeName);

        return $attribute ? $attribute->pivot->value : null;
    }
}
