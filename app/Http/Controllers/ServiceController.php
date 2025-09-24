<?php

namespace App\Http\Controllers;

use App\Services\ServiceService;
use Illuminate\View\View;

class ServiceController
{
    public function __construct(
        private ServiceService $serviceService
    ) {}

    public function show(int $serviceId): View
    {
        $service = $this->serviceService->getServiceById($serviceId);

        return view('services', [
            'content' => $service->description,
        ]);
    }
}
