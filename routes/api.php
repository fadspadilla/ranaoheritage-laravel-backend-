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
use App\Http\Controllers\DescriptionController;
use App\Http\Controllers\ConservationController;
use App\Http\Controllers\SignificanceController;
use App\Http\Controllers\NaturalController;
use App\Http\Controllers\ImmovableController;
use App\Http\Controllers\MovableController;

//Public Routes
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);    
    /** heritages */
Route::get('heritage/search', [HeritagesController::class, 'search']);   
Route::get('catalog', [HeritagesController::class, 'catalog']);   
Route::get('catalog-details/{id}', [HeritagesController::class, 'catalogDetails']);   
    /** multimedia */
Route::get('images/heritage-images/{id}', [ImagesController::class, 'heritageImages']);
Route::get('images/singleImage/{id}', [ImagesController::class, 'singleImage']);
Route::get('videos/heritage-videos/{id}', [VideosController::class, 'heritageVideos']);
    /** places */

Route::get('mun-list', [ MunicipalitiesController::class, 'munList']);
Route::get('mun-basic-list', [ MunicipalitiesController::class, 'munBasicDetails']);
Route::get('mun-details/{id}', [ MunicipalitiesController::class, 'munDetails']);
Route::get('mun-count/{id}', [MunicipalitiesController::class, 'counter']);    
    /** Locations */
Route::get('location/map', [LocationsController::class, 'location']);

//Private Routes
Route::middleware('auth:sanctum')->group(function () {    
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('auth-user', [AuthController::class, 'user']);
    Route::put('auth-user/name/{id}', [AuthController::class, 'updateName']);
    Route::put('auth-user/username/{id}', [AuthController::class, 'updateUsername']);
    Route::put('auth-user/password/{id}', [AuthController::class, 'updatePassword']);
    Route::put('auth-user/update/{id}', [AuthController::class, 'updateUser']);

    //categories
    Route::resource('categories', CategoriesController::class);
    //** end of categories */

    //icons
    Route::resource('icons', IconsController::class);
    Route::post('update-icon/{id}', [IconsController::class, 'updateIcon']);

    //Municipalities
    Route::resource('municipalities', MunicipalitiesController::class);
    Route::post('update-municipality/{id}', [ MunicipalitiesController::class, 'updateMunicipality']);    
    
    //description
    Route::post('descriptions', [DescriptionController::class, 'store']);
    Route::post('descriptions/{id}', [DescriptionController::class, 'update']);

    //conservations
    Route::post('conservations', [ConservationController::class, 'store']);
    Route::post('conservations/{id}', [ConservationController::class, 'update']);
    
    //significances
    Route::post('significances', [SignificanceController::class, 'store']);
    Route::post('significances/{id}', [SignificanceController::class, 'update']);

    //natural heritage
    Route::post('natural-heritage', [NaturalController::class, 'store']);
    Route::post('natural-heritage/{id}', [NaturalController::class, 'update']);

    //immovable heritage
    Route::post('immovable-heritage', [ImmovableController::class, 'store']);
    Route::post('immovable-heritage/{id}', [ImmovableController::class, 'update']);

    //movable heritage
    Route::post('movable-heritage', [MovableController::class, 'store']);
    Route::post('movable-heritage/{id}', [MovableController::class, 'update']);

    //locations
    Route::resource('locations', LocationsController::class);

    //Address
    Route::resource('addresses', AddressesController::class);

    //Heritage
    Route::resource('heritages', HeritagesController::class); 
    Route::get('polariod', [HeritagesController::class, 'dashboard']);     
    Route::get('edit-heritage/{id}', [HeritagesController::class, 'editHeritage']);    
    Route::get('heritage/count', [HeritagesController::class, 'counter']);    
    Route::get('heritage/search', [HeritagesController::class, 'search']);    

    //Image
    Route::resource('images', ImagesController::class);
    Route::get('image/count', [ImagesController::class, 'counter']);

    //Video
    Route::resource('videos', VideosController::class);    
    Route::get('video/count', [VideosController::class, 'counter']);


    // Route::resource('user', UserController::class);    
});
 