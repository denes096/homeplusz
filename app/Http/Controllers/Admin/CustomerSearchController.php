<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerSearchRequest;
use App\Models\CustomerSearch;
use App\Models\Customers;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class CustomerSearchController extends Controller
{
    public function index(): View
    {
        $searches = CustomerSearch::with('customer')->latest()->paginate(15);
        
        return view('admin.customer-searches.index', [
            'title' => 'Mentett keresések',
            'pageTitle' => 'Mentett keresések',
            'searches' => $searches,
            'createRoute' => 'admin.customer-searches.create',
            'editRoute' => 'admin.customer-searches.edit',
            'destroyRoute' => 'admin.customer-searches.destroy',
            'showRoute' => 'admin.customer-searches.show',
        ]);
    }

    public function create(): View
    {
        $customers = Customers::orderBy('name_0')->get()->pluck('name_0', 'id')->toArray();
        
        return view('admin.customer-searches.create', [
            'title' => 'Új keresés',
            'pageTitle' => 'Új keresés',
            'customers' => $customers,
            'storeRoute' => 'admin.customer-searches.store',
            'indexRoute' => 'admin.customer-searches.index',
        ]);
    }

    public function store(CustomerSearchRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        
        // Ensure search is valid JSON
        $searchData = json_decode($validated['search'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['search' => 'Érvénytelen JSON formátum.']);
        }
        
        CustomerSearch::create($validated);
        
        return redirect()->route('admin.customer-searches.index')
            ->with('success', 'Keresés sikeresen létrehozva.');
    }

    public function show(CustomerSearch $customerSearch): View
    {
        $customerSearch->load('customer');
        
        $searchArray = json_decode($customerSearch->search, true);
        
        return view('admin.customer-searches.show', [
            'title' => 'Keresés részletei',
            'pageTitle' => 'Keresés részletei',
            'search' => $customerSearch,
            'searchArray' => $searchArray,
            'indexRoute' => 'admin.customer-searches.index',
            'editRoute' => 'admin.customer-searches.edit',
        ]);
    }

    public function edit(CustomerSearch $customerSearch): View
    {
        $customers = Customers::orderBy('name_0')->get()->pluck('name_0', 'id')->toArray();
        
        return view('admin.customer-searches.edit', [
            'title' => 'Keresés szerkesztése',
            'pageTitle' => 'Keresés szerkesztése',
            'search' => $customerSearch,
            'customers' => $customers,
            'updateRoute' => 'admin.customer-searches.update',
            'indexRoute' => 'admin.customer-searches.index',
        ]);
    }

    public function update(CustomerSearchRequest $request, CustomerSearch $customerSearch): RedirectResponse
    {
        $validated = $request->validated();
        
        // Ensure search is valid JSON
        $searchData = json_decode($validated['search'], true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return redirect()->back()
                ->withInput()
                ->withErrors(['search' => 'Érvénytelen JSON formátum.']);
        }
        
        $customerSearch->update($validated);
        
        return redirect()->route('admin.customer-searches.index')
            ->with('success', 'Keresés sikeresen frissítve.');
    }

    public function destroy(CustomerSearch $customerSearch): RedirectResponse
    {
        $customerSearch->delete();
        
        return redirect()->route('admin.customer-searches.index')
            ->with('success', 'Keresés sikeresen törölve.');
    }
}

