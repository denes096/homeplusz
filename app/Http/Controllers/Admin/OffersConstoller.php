<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class OffersConstoller extends CrudController
{
    public function index()
    {
        return view('vendor.backpack.base.offers', []);
    }
}
