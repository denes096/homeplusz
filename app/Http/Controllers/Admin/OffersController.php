<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Http\Controllers\CrudController;

class OffersController extends CrudController
{
    public function index()
    {
        return view('vendor.backpack.base.offers', []);
    }
}
