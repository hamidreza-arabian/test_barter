<?php

use App\Http\Controllers\FileCommentController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\EstateTypeController;
use App\Http\Controllers\EstateFieldController;
use App\Http\Controllers\EstateTypeFieldController;
use App\Http\Controllers\WantEstateFieldController;
use App\Http\Controllers\MainRegisterEstateController;
use App\Http\Controllers\RegisterEstateController;
use App\Http\Controllers\EstateCommentController;
use App\Http\Controllers\ProvinceController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\RegionController;
use App\Http\Controllers\DistrictController;

Route::prefix('auth')->group(function(){
    Route::middleware('auth:sanctum')->get('is-logged', [AuthController::class, 'isLogged']);
    Route::post('phone-number',[AuthController::class, 'sendPhoneNumber']);
    Route::post('code',[AuthController::class, 'validateCode']);
    Route::get('logout',[AuthController::class, 'logout'])->middleware('auth:sanctum');
});
Route::middleware(['auth:sanctum', 'check.active.user', 'check.employee'])->prefix('admin')->group(function (){
    Route::middleware(['check.admin'])->prefix('')->group(function (){
        Route::apiResource('user', UserController::class);
        Route::apiResource('add-estate-type-field', EstateTypeFieldController::class);
        Route::apiResource('add-want-estate-type-field', WantEstateFieldController::class);
        Route::apiResource('province', ProvinceController::class);
        Route::apiResource('city', CityController::class);
        Route::apiResource('region', RegionController::class);
        Route::apiResource('district', DistrictController::class);
        Route::get('user-special', [UserController::class, 'userSpecial']);
    });
    Route::apiResource('role', RoleController::class);
    Route::apiResource('user', UserController::class)->only(['index', 'show']);
    Route::apiResource('estate-type', EstateTypeController::class);
    Route::get('estate-type-field', [EstateTypeController::class, 'get_estates_with_fields']);
    Route::get('want-estate-type-field', [EstateTypeController::class, 'get_want_estates_with_fields']);
    Route::apiResource('estate-field', EstateFieldController::class);
    Route::apiResource('province', ProvinceController::class)->only(['index']);
    Route::apiResource('city', CityController::class)->only(['show']);
    Route::apiResource('region', RegionController::class)->only(['show']);
    Route::apiResource('district', DistrictController::class)->only(['show']);
    Route::apiResource('estate', MainRegisterEstateController::class);
    Route::apiResource('estate-comment', EstateCommentController::class);
    Route::post('estate-by-phone', [MainRegisterEstateController::class, 'estateByPhone']);
    Route::post('estate-filter', [RegisterEstateController::class, 'getEstatesWithFilter']);
    Route::get('estate-comment', [EstateCommentController::class, 'estateComment']);
    Route::post('add-want', [MainRegisterEstateController::class, 'addWant']);
    Route::post('add-asset', [MainRegisterEstateController::class, 'addAssets']);
    Route::get('want-search', [MainRegisterEstateController::class, 'wantSearch']);
    Route::get('advance-search', [MainRegisterEstateController::class, 'searchByAsset']);
    Route::get('adviser', [UserController::class, 'adviser']);
    Route::apiResource('file-comment', FileCommentController::class);
    Route::put('want',[MainRegisterEstateController::class, 'deleteWant']);
});


