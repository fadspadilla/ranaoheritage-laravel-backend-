<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
//Custom Controllers
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ProvincesController;
use App\Http\Controllers\IconsController;
use App\Http\Controllers\MunicipalitiesController;
use App\Http\Controllers\LocationsController;
use App\Http\Controllers\AddressesController;
use App\Http\Controllers\HeritagesController;
use App\Http\Controllers\ImagesController;
use App\Http\Controllers\VideosController;

//Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);


//Private Routes
Route::middleware('auth:sanctum')->group(function () {    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('auth-user', [AuthController::class, 'user']);
    Route::put('auth-user/name/{id}', [AuthController::class, 'updateName']);
    Route::put('auth-user/username/{id}', [AuthController::class, 'updateUsername']);
    Route::put('auth-user/password/{id}', [AuthController::class, 'updatePassword']);

    //categories
    Route::resource('categories', CategoriesController::class);
    //** end of categories */

    //provinces
    Route::resource('provinces', ProvincesController::class);
    Route::get('province-list', [ProvincesController::class, 'provinceList']);
    Route::post('update-province/{id}', [ProvincesController::class, 'updateProvince']);
    Route::get('province/count', [ProvincesController::class, 'counter']);
    Route::get('newProvince', [ProvincesController::class, 'newProvince']);

    //icons
    Route::resource('icons', IconsController::class);
    Route::post('update-icon/{id}', [IconsController::class, 'updateIcon']);

    //Municipalities
    Route::resource('municipalities', MunicipalitiesController::class);
    Route::get('municipalities/mun-list-province/{id}', [ MunicipalitiesController::class, 'munInProv']);
    Route::get('mun-list', [ MunicipalitiesController::class, 'munList']);
    Route::get('mun-details/{id}', [ MunicipalitiesController::class, 'munDetails']);
    Route::post('update-municipality/{id}', [ MunicipalitiesController::class, 'updateMunicipality']);
    Route::get('mun-count/{id}', [MunicipalitiesController::class, 'counter']);

    //Locations
    Route::resource('locations', LocationsController::class);

    //Address
    Route::resource('addresses', AddressesController::class);

    //Heritage
    Route::resource('heritages', HeritagesController::class);
    Route::get('catalog', [HeritagesController::class, 'catalog']);    
    Route::get('polariod', [HeritagesController::class, 'dashboard']);    
    Route::get('catalog-details/{id}', [HeritagesController::class, 'catalogDetails']);    
    Route::get('edit-heritage/{id}', [HeritagesController::class, 'editHeritage']);    
    Route::get('heritage/count', [HeritagesController::class, 'counter']);    

    //Image
    Route::resource('images', ImagesController::class);
    Route::get('images/heritage-images/{id}', [ImagesController::class, 'heritageImages']);
    Route::get('images/singleImage/{id}', [ImagesController::class, 'singleImage']);
    Route::get('image/count', [ImagesController::class, 'counter']);

    //Video
    Route::resource('videos', VideosController::class);
    Route::get('videos/heritage-videos/{id}', [VideosController::class, 'heritageVideos']);
    Route::get('video/count', [VideosController::class, 'counter']);


    // Route::resource('user', UserController::class);    
});
 