<?php

namespace App\Http\Controllers;

use App\Services\InformationService;
use App\Services\ServiceService;
use Illuminate\View\View;

class InformationController
{

    public function __construct(
        private InformationService $informationService
    )
    {
    }

    public function show(int $informationId): View{
        $information = $this->informationService->getInformationById($informationId);
        return view('information', [
            'content' => $information->description,
        ]);
    }
}
