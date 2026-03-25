<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CustomerOffer;
use Illuminate\View\View;

class OffersController extends Controller
{
    public function index(): View
    {
        $offers = CustomerOffer::with(['customer', 'customerSearch'])
            ->orderBy('sent_at', 'desc')
            ->paginate(20);
        
        return view('admin.offers.index', [
            'title' => 'Ajánlatok',
            'pageTitle' => 'Ajánlatok',
            'offers' => $offers,
        ]);
    }
}
