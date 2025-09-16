<?php

use App\Models\Customers;
use App\Models\CustomerSearch;

it('creates customer with search and can view show page', function () {
    // minimal test: create customer and a saved search, then visit show
    $customer = Customers::create([
        'name_0' => 'Test User',
        'ekod' => 'TST1',
        'kategoria' => 1,
    ]);

    $search = CustomerSearch::create([
        'customer_id' => $customer->id,
        'search' => json_encode(['price_min' => 1000000]),
    ]);

    $this->get('/admin/customers/'.$customer->id)
        ->assertStatus(200)
        ->assertSee('Vevő:');
});
