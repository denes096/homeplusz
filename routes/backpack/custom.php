<?php

use App\Http\Controllers\Admin\PropertyCrudController;
use App\Http\Controllers\Admin\PropertyImageDownloaderCrudController;
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
    Route::crud('customer', 'CustomersCrudController');
    Route::crud('property-image-downloader', 'PropertyImageDownloaderCrudController');

    Route::get('find/{unique_id}', [PropertyCrudController::class, 'findPropertyOrProject'])->name('find');
    Route::get('property-image-downloader/{unique_id}', [PropertyImageDownloaderCrudController::class, 'download'])->name('admin.property-image-downloader');
    Route::post('property/{id}/toggle-active', [PropertyCrudController::class, 'toggleActive'])->name('admin.property.toggle-active');
    Route::get('property/{id}/matching-searches', [PropertyCrudController::class, 'showMatchingSearches'])->name('admin.property.matching-searches');
    Route::post('property/{propertyId}/send-to-search/{searchId}', [PropertyCrudController::class, 'sendToMatchingSearch'])->name('admin.property.send-to-search');
    Route::crud('customers', 'CustomersCrudController');
    Route::get('customers/{id}/execute-search/{searchId}', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'executeSearch']);
    Route::post('customers/{id}/send-property-email', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'sendPropertyEmail']);
    Route::post('customers/{id}/send-offer', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'sendOffer']);
    Route::get('customers/{id}/offers/{searchId}', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'getOffers']);
    Route::get('offers/{id}/details', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'getOfferDetails']);
    Route::crud('slider-images', 'SliderImagesCrudController');
    Route::crud('customer-search', 'CustomerSearchCrudController');
    Route::get('offers', [\App\Http\Controllers\Admin\OffersConstoller::class, 'index'])->name('backpack.offers.index');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
