<?php

namespace App\Services;

use App\Models\Service;
use App\Models\ServiceCategory;
use Illuminate\Support\Collection;

class ServiceService
{
    public function serviceCategories(): Collection{
        return ServiceCategory::with('services')->get();
    }

    public function getServiceById(int $serviceId): Service
    {
        return Service::findOrFail($serviceId);
    }
}
