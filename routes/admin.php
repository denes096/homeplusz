<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PropertyController;
use App\Http\Controllers\Admin\ProjectController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\LabelController;
use App\Http\Controllers\Admin\PropertyTypeController;
use App\Http\Controllers\Admin\PropertySubtypeController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\SettlementPartController;
use App\Http\Controllers\Admin\SettlementGroupController;
use App\Http\Controllers\Admin\PropertyAttributeController;
use App\Http\Controllers\Admin\PropertyAttributeCategoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceCategoryController;
use App\Http\Controllers\Admin\InformationController;
use App\Http\Controllers\Admin\InformationCategoryController;
use App\Http\Controllers\Admin\StaticPageController;
use App\Http\Controllers\Admin\SliderImageController;
use App\Http\Controllers\Admin\CustomerSearchController;
use App\Http\Controllers\Admin\OffersController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['web', 'auth', 'admin'])->name('admin.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Properties
    Route::resource('properties', PropertyController::class);
    Route::post('properties/{property}/toggle-active', [PropertyController::class, 'toggleActive'])->name('properties.toggle-active');
    Route::get('properties/{property}/matching-searches', [PropertyController::class, 'showMatchingSearches'])->name('properties.matching-searches');
    Route::post('properties/{property}/send-to-search/{searchId}', [PropertyController::class, 'sendToMatchingSearch'])->name('properties.send-to-search');
    Route::post('properties/{property}/upload-document', [PropertyController::class, 'uploadDocument'])->name('properties.upload-document');
    Route::delete('properties/{property}/document/{documentId}', [PropertyController::class, 'deleteDocument'])->name('properties.delete-document');
    Route::get('find/{unique_id}', [PropertyController::class, 'findPropertyOrProject'])->name('find');
    
    // Projects
    Route::resource('projects', ProjectController::class);
    Route::post('projects/{projectId}/upload-document', [ProjectController::class, 'uploadDocument'])->name('projects.upload-document');
    Route::delete('projects/document/{documentId}/delete', [ProjectController::class, 'deleteDocument'])->name('projects.document.delete');
    
    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('customers/{id}/execute-search/{searchId}', [CustomerController::class, 'executeSearch'])->name('customers.execute-search');
    Route::post('customers/{id}/send-property-email', [CustomerController::class, 'sendPropertyEmail'])->name('customers.send-property-email');
    Route::post('customers/{id}/send-offer', [CustomerController::class, 'sendOffer'])->name('customers.send-offer');
    Route::get('customers/{id}/offers/{searchId}', [CustomerController::class, 'getOffers'])->name('customers.offers');
    Route::get('offers/{id}/details', [CustomerController::class, 'getOfferDetails'])->name('offers.details');
    Route::post('customers/{customerId}/add-contact', [CustomerController::class, 'addContact'])->name('customers.add-contact');
    Route::delete('customers/contact/{contactId}/delete', [CustomerController::class, 'deleteContact'])->name('customers.delete-contact');
    Route::post('customers/{customerId}/upload-document', [CustomerController::class, 'uploadDocument'])->name('customers.upload-document');
    Route::delete('customers/document/{documentId}/delete', [CustomerController::class, 'deleteDocument'])->name('customers.delete-document');
    
    // Partners
    Route::resource('partners', PartnerController::class);
    Route::post('partners/{partnerId}/add-contact', [PartnerController::class, 'addContact'])->name('partners.add-contact');
    Route::delete('partners/contact/{contactId}/delete', [PartnerController::class, 'deleteContact'])->name('partners.delete-contact');
    Route::post('partners/{partnerId}/upload-document', [PartnerController::class, 'uploadDocument'])->name('partners.upload-document');
    Route::delete('partners/document/{documentId}/delete', [PartnerController::class, 'deleteDocument'])->name('partners.delete-document');
    
    // Clients
    Route::resource('clients', ClientController::class);
    Route::post('clients/{clientId}/add-contact', [ClientController::class, 'addContact'])->name('clients.add-contact');
    Route::delete('clients/contact/{contactId}/delete', [ClientController::class, 'deleteContact'])->name('clients.delete-contact');
    Route::post('clients/{clientId}/upload-document', [ClientController::class, 'uploadDocument'])->name('clients.upload-document');
    Route::delete('clients/document/{documentId}/delete', [ClientController::class, 'deleteDocument'])->name('clients.delete-document');
    
    // Users
    Route::resource('users', UserController::class);
    
    // Labels
    Route::resource('labels', LabelController::class);
    
    // Property Types
    Route::resource('property-types', PropertyTypeController::class);
    
    // Property Subtypes
    Route::resource('property-subtypes', PropertySubtypeController::class);
    
    // Settlements
    Route::resource('settlements', SettlementController::class);
    
    // Settlement Parts
    Route::resource('settlement-parts', SettlementPartController::class);
    
    // Settlement Groups
    Route::resource('settlement-groups', SettlementGroupController::class);
    
    // Property Attributes
    Route::resource('property-attributes', PropertyAttributeController::class);
    
    // Property Attribute Categories
    Route::resource('property-attribute-categories', PropertyAttributeCategoryController::class);
    
    // Services
    Route::resource('services', ServiceController::class);
    
    // Service Categories
    Route::resource('service-categories', ServiceCategoryController::class);
    
    // Information
    Route::resource('information', InformationController::class);
    
    // Information Categories
    Route::resource('information-categories', InformationCategoryController::class);
    
    // Static Pages
    Route::resource('static-pages', StaticPageController::class);
    
    // Slider Images
    Route::resource('slider-images', SliderImageController::class);
    
    // Customer Searches
    Route::resource('customer-searches', CustomerSearchController::class);
    
    // Offers
    Route::get('offers', [OffersController::class, 'index'])->name('offers.index');
});

