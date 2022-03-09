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
    // Route::post('categories', [CategoriesController::class, 'store']);
    // Route::get('categories', [CategoriesController::class, 'index']);
    // Route::get('categories/${id}', [CategoriesController::class, 'show']);
    //** end of categories */

    //provinces
    Route::resource('provinces', ProvincesController::class);
    
    //icons
    Route::resource('icons', IconsController::class);
    Route::post('update-icon', [IconsController::class, 'updateIcon']);

    //Municipalities
    Route::resource('municipalities', MunicipalitiesController::class);
    Route::get('municipalities/mun-in-prov/{id}', [ MunicipalitiesController::class, 'munInProv']);

    //Locations
    Route::resource('locations', LocationsController::class);

    //Address
    Route::resource('addresses', AddressesController::class);

    //Heritage
    Route::resource('heritages', HeritagesController::class);
    Route::get('catalog', [HeritagesController::class, 'catalog']);

    //Image
    Route::resource('images', ImagesController::class);
    Route::get('images/heritage-images/{id}', [ImagesController::class, 'heritageImages']);
    Route::get('images/singleImage/{id}', [ImagesController::class, 'singleImage']);

    //Video
    Route::resource('videos', VideosController::class);


    // Route::resource('user', UserController::class);    
});
 