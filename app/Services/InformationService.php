<?php

namespace App\Services;

use App\Models\Information;
use App\Models\InformationCategory;
use Illuminate\Support\Collection;

class InformationService
{
    public function informationCategories(): Collection
    {
        return InformationCategory::with('informations')->get();
    }

    public function getInformationById(int $informationId): Information
    {
        return Information::findOrFail($informationId);
    }
}
