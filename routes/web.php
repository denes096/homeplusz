<?php

use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\PropertyController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\StaticPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('dashboard');
Route::get('/kornyek/{settlementAreaId}-{name}-es-kornyeke', [HomeController::class, 'searchBySettlementGroup'])
    ->where('settlementAreaId', '[0-9]+')
    ->where('name', '[A-Za-z]+');

// Admin API routes
Route::prefix('admin/api')->middleware('auth')->group(function () {
    Route::get('/property-search', [PropertyController::class, 'searchForDropdown']);
});

// Property document routes
Route::prefix('admin/property')->middleware('auth')->group(function () {
    Route::post('/{propertyId}/upload-document', [\App\Http\Controllers\Admin\PropertyCrudController::class, 'uploadDocument']);
    Route::delete('/{propertyId}/document/{documentId}', [\App\Http\Controllers\Admin\PropertyCrudController::class, 'deleteDocument']);
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
require __DIR__.'/admin.php';
// require __DIR__.'/backpack/custom.php'; // Commented out - using new admin routes

Route::get('/szolgaltatasok/{serviceId}-{name}', [ServiceController::class, 'show']);
Route::get('/informaciok/{informationId}-{name}', [InformationController::class, 'show']);

Route::get('/ingatlanok', [PropertyController::class, 'list'])->name('property.list');
Route::get('/ingatlan/kod/{code}', [PropertyController::class, 'getByCode'])->name('property.get-by-code');
Route::get('/ingatlan/{id}', [PropertyController::class, 'show'])->name('property.show');
Route::match(['get', 'post'], '/kedvenceim', [PropertyController::class, 'favorites'])->name('property.favorites');

Route::get('/projekt/{id}', [ProjectController::class, 'list'])->name('project.list');
Route::get('/projekt/getNextPropertyId/{id}', [ProjectController::class, 'getNextPropertyId'])->name('project.getNextPropertyId');

Route::get('/bemutatkozas', [AboutUsController::class, 'show'])->name('aboutUs.show');
Route::get('/bemutatkozas/{id}', [AboutUsController::class, 'showAgentDetails'])->name('aboutUs.show-agent-details');

Route::get('/{slug}', [StaticPageController::class, 'show']);
