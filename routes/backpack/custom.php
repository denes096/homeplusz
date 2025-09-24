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
    Route::get('dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
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
    Route::post('project/{projectId}/upload-document', [\App\Http\Controllers\Admin\ProjectCrudController::class, 'uploadDocument'])->name('admin.project.upload-document');
    Route::delete('project/document/{documentId}/delete', [\App\Http\Controllers\Admin\ProjectCrudController::class, 'deleteDocument'])->name('admin.project.document.delete');
    Route::crud('partners', 'PartnersCrudController');
    Route::post('partners/{partnerId}/add-contact', [\App\Http\Controllers\Admin\PartnersCrudController::class, 'addContact']);
    Route::delete('partners/contact/{contactId}/delete', [\App\Http\Controllers\Admin\PartnersCrudController::class, 'deleteContact']);
    Route::post('partners/{partnerId}/upload-document', [\App\Http\Controllers\Admin\PartnersCrudController::class, 'uploadDocument']);
    Route::delete('partners/document/{documentId}/delete', [\App\Http\Controllers\Admin\PartnersCrudController::class, 'deleteDocument']);
    Route::crud('clients', 'ClientsCrudController');
    Route::post('clients/{clientId}/add-contact', [\App\Http\Controllers\Admin\ClientsCrudController::class, 'addContact']);
    Route::delete('clients/contact/{contactId}/delete', [\App\Http\Controllers\Admin\ClientsCrudController::class, 'deleteContact']);
    Route::post('clients/{clientId}/upload-document', [\App\Http\Controllers\Admin\ClientsCrudController::class, 'uploadDocument']);
    Route::delete('clients/document/{documentId}/delete', [\App\Http\Controllers\Admin\ClientsCrudController::class, 'deleteDocument']);
    Route::crud('customer', 'CustomersCrudController');
    Route::crud('property-image-downloader', 'PropertyImageDownloaderCrudController');

    Route::get('find/{unique_id}', [PropertyCrudController::class, 'findPropertyOrProject'])->name('find');
    Route::get('property-image-downloader/{unique_id}', [PropertyImageDownloaderCrudController::class, 'download'])->name('admin.property-image-downloader');
    Route::post('property/{id}/toggle-active', [PropertyCrudController::class, 'toggleActive'])->name('admin.property.toggle-active');
    Route::get('property/{id}/matching-searches', [PropertyCrudController::class, 'showMatchingSearches'])->name('admin.property.matching-searches');
    Route::post('property/{propertyId}/send-to-search/{searchId}', [PropertyCrudController::class, 'sendToMatchingSearch'])->name('admin.property.send-to-search');

    // Inactive properties route
    // Route::get('property-inactive', [PropertyCrudController::class, 'listInactive'])->name('admin.property.inactive');

    // Active properties route
    //Route::get('property-active', [PropertyCrudController::class, 'listActive'])->name('admin.property.active');

    // Property listing routes
    Route::crud('customers', 'CustomersCrudController');
    Route::get('customers/{id}/execute-search/{searchId}', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'executeSearch']);
    Route::post('customers/{id}/send-property-email', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'sendPropertyEmail']);
    Route::post('customers/{id}/send-offer', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'sendOffer']);
    Route::get('customers/{id}/offers/{searchId}', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'getOffers']);
    Route::get('offers/{id}/details', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'getOfferDetails']);
    Route::post('customers/{customerId}/add-contact', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'addContact']);
    Route::delete('customers/contact/{contactId}/delete', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'deleteContact']);
    Route::post('customers/{customerId}/upload-document', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'uploadDocument']);
    Route::delete('customers/document/{documentId}/delete', [\App\Http\Controllers\Admin\CustomersCrudController::class, 'deleteDocument']);
    Route::crud('slider-images', 'SliderImagesCrudController');
    Route::crud('customer-search', 'CustomerSearchCrudController');
    Route::get('offers', [\App\Http\Controllers\Admin\OffersConstoller::class, 'index'])->name('backpack.offers.index');
}); // this should be the absolute last line of this file

/**
 * DO NOT ADD ANYTHING HERE.
 */
