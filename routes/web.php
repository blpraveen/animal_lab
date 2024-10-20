<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\language\LanguageController;
use App\Http\Controllers\pages\HomePage;
use App\Http\Controllers\pages\Page2;
use App\Http\Controllers\pages\RoleController;
use App\Http\Controllers\pages\MiscError;
use App\Http\Controllers\authentications\LoginBasic;
use App\Http\Controllers\authentications\RegisterBasic;
use App\Http\Controllers\pages\BreedingsController;
use App\Http\Controllers\pages\RoomsController;
use App\Http\Controllers\pages\SpeciesController;
use App\Http\Controllers\pages\StaffsController;
use App\Http\Controllers\pages\StrainsController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Main Page Route
Route::group(['middleware' => ['auth']], function() {
    Route::get('/', [HomePage::class, 'index'])->name('pages-home');
    Route::get('/page-2', [Page2::class, 'index'])->name('pages-page-2');
    //Route::get('/roles', [Roles::class, 'index'])->name('pages-roles');
    Route::post('/roles/update/{id}', [RoleController::class, 'update_roles'])->name('roles-update');
    Route::post('/staffs/update/{id}', [StaffsController::class, 'update_users'])->name('staffs-update');
    
    // locale
    Route::get('lang/{locale}', [LanguageController::class, 'swap']);

    // pages
    Route::get('/pages/misc-error', [MiscError::class, 'index'])->name('pages-misc-error');
    Route::resource('roles', RoleController::class);
    Route::resource('staffs', StaffsController::class);
    Route::resource('species', SpeciesController::class);
    Route::resource('strains', StrainsController::class);
    Route::resource('rooms', RoomsController::class);
    Route::get('/breeding', [BreedingsController::class, 'index'])->name('pages-breeding');
    Route::post('/get_species', [BreedingsController::class, 'get_species'])->name('get.species');
    Route::post('/get_strains', [BreedingsController::class, 'get_strains'])->name('get.strains');
    Route::post('/breeding-listing', [BreedingsController::class, 'breeding_store'])->name('breeding.store');
    Route::post('/breeding-store', [BreedingsController::class, 'breeding_store_new'])->name('breeding_store');
    Route::get('/breeding-delivery/{id}', [BreedingsController::class, 'breeding_delivery'])->name('breeding.delivery');
    Route::get('/breeding-weaning/{id}', [BreedingsController::class, 'breeding_weaning'])->name('breeding.weaning');
    Route::get('/breeding-summary/{id}', [BreedingsController::class, 'breeding_summary'])->name('breeding.summary');
    Route::post('/delivery-update/{id}', [BreedingsController::class, 'breeding_update'])->name('breeding.update');
    Route::post('/weaning-update/{id}', [BreedingsController::class, 'weaning_update'])->name('weaning.update');
    Route::post('/weaning-update-mutant/{id}', [BreedingsController::class, 'weaning_update_mutant'])->name('weaning.update.mutant');
    Route::get('/breeding-weaning-mutant/{id}', [BreedingsController::class, 'breeding_mutant'])->name('breeding.mutant');
    Route::get('/breeding-summary-mutant/{id}', [BreedingsController::class, 'breeding_summary_mutant'])->name('breeding.summary.mutant');
    
});
// authentication
Route::get('/auth/login', [LoginBasic::class, 'index'])->name('auth-login');
Route::get('/auth/register-basic', [RegisterBasic::class, 'index'])->name('auth-register-basic');

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
