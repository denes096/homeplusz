<?php

use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\CRUD.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('property', 'PropertyCrudController');
    Route::crud('label', 'LabelCrudController');
    Route::crud('property-attribute', 'PropertyAttributeCrudController');
    Route::crud('property-attribute-category', 'PropertyAttributeCategoryCrudController');
    Route::crud('settlement', 'SettlementCrudController');
    Route::crud('settlement-part', 'SettlementPartCrudController');
    Route::crud('property-type', 'PropertyTypeCrudController');
    Route::crud('property-subtype', 'PropertySubtypeCrudController');
    Route::crud('settlement-group', 'SettlementGroupCrudController');
    Route::crud('service-category', 'ServiceCategoryCrudController');
    Route::crud('service', 'ServiceCrudController');
    Route::crud('information-category', 'InformationCategoryCrudController');
    Route::crud('information', 'InformationCrudController');
    Route::crud('static-page', 'StaticPageCrudController');
    Route::crud('project', 'ProjectCrudController');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
