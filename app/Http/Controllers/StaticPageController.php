<?php

namespace App\Http\Controllers;

use App\Services\LabelService;
use App\Services\PropertyService;
use App\Services\SettlementService;
use App\Services\StaticPageService;
use Illuminate\View\View;

class StaticPageController extends Controller
{

    public function __construct(
        private StaticPageService $staticPageService,
    )
    {
    }

    public function show($slug): View{
        $staticPage = $this->staticPageService->getStaticPageBySlug($slug);

        return view('static-page', [
            'content' => $staticPage->content,
        ]);
    }
}
