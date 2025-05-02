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
        $propertyQuery = Property::where("id", "=", $id)
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list',  true);
            } ])
        ;

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
            $query->where('show_in_list',  true);
        } ])
        ;

        if (!empty($limit)) {
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
                $query->where('show_in_list',  true);
            } ]);
        if (!empty($limit)) {
            $propertyQuery->limit($limit);
        }
        return $propertyQuery->get();

    }

    public function getFeaturedProperties(?int $limit = null): Collection
    {
        $propertyQuery = Property::where('featured',1)
            ->with('labels')
            ->with('settlement')
            ->with('settlementPart')
            ->with('propertyType')
            ->with('propertySubtype')
            ->with(['attributes' => function ($query) {
                $query->where('show_in_list',  true);
            } ]);
        if (!empty($limit)) {
            $propertyQuery->limit($limit);
        }
        return $propertyQuery->get();
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
                $query->where('show_in_list',  true);
            } ])
            ->where('ad_type', $request->input('ad_type'));

        // 2. Települések (több is lehet)
        if ($request->filled('settlements')) {
            $query->whereHas('settlement', function ($query) use ($request) {
                $query->whereIn('id', $request->input('settlements'));
            });
        } else {
            $query->with('settlement');
        }

        // 3. Ingatlantípusok (több is lehet)
        if ($request->filled('property_types')) {
            $query->whereIn('property_type_id', $request->input('property_types'));
        }

        if ($request->filled('number_of_rooms_min') || $request->filled('number_of_rooms_max')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('name', 'number_of_rooms');

                if ($request->filled('number_of_rooms_min')) {
                    $q->whereRaw(
                        "CAST(property_property_attribute.value AS UNSIGNED) >= ?",
                        [$request->input('number_of_rooms_min')]
                    );
                }

                if ($request->filled('number_of_rooms_max')) {
                    $q->whereRaw(
                        "CAST(property_property_attribute.value AS UNSIGNED) <= ?",
                        [$request->input('number_of_rooms_max')]
                    );
                }
            });
        }

        // 5. Méret intervallum
        if ($request->filled('property_area_min') || $request->filled('property_area_max')) {
            $query->whereHas('attributes', function ($q) use ($request) {
                $q->where('name', 'property_area');

                if ($request->filled('property_area_min')) {
                    $q->whereRaw(
                        "CAST(property_property_attribute.value AS UNSIGNED) >= ?",
                        [$request->input('property_area_min')]
                    );
                }

                if ($request->filled('property_area_max')) {
                    $q->whereRaw(
                        "CAST(property_property_attribute.value AS UNSIGNED) <= ?",
                        [$request->input('property_area_max')]
                    );
                }
            });
        }
        if ($request->filled('price_min')) {
            $query->where('price', '>=', $request->input('price_min'));
        }
        if ($request->filled('price_max')) {
            $query->where('price', '<=', $request->input('price_max'));
        }


        return $query->paginate(15);
    }
}
