<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LocationController;

Route::get('/login', function () {
    return view('login');
})->name('login');
Route::post('/login',[AppController::class, 'userLogin'])->name('userLogin');
Route::middleware(['auth'])->group(function(){

    Route::get('/', [AppController::class, 'home'])->name('home');
    Route::get('/newLocation', [AppController::class, 'getAllLocations'])->name('getAllLocations');

    Route::get('/addMaterial', [AppController::class, 'getAllMaterials'])->name(('getAllMaterials'));
    Route::get('/newUser', [AppController::class, 'getAllUsers'])->name(('newUser'));
    Route::get('/editUser', [AppController::class, 'editUser'])->name('editUser');
    Route::get('/editLocation/{id}', [AppController::class, 'editLocation'])->name('editLocation');
    Route::get('/editMaterial/{id}',[AppController::class, 'editMaterial'])->name('editMaterial');
    Route::get('/editProfile/{id}',[AppController::class, 'editProfile'])->name('editProfile');

    Route::post('/updateProfile/{id}', [AppController::class, 'updateProfile'])->name('updateProfile');
    Route::post('/updateMaterial/{id}', [AppController::class, 'updateMaterial'])->name('updateMaterial');
    Route::post('/updateLocation/{id}', [AppController::class, 'updateLocation'])->name('updateLocation');
    Route::post('/addLocation', [AppController::class, 'addLocation'])->name('addLocation');
    Route::post('/newMaterial', [AppController::class, 'addMaterial'])->name('newMaterial');
    Route::post('/createUser', [AppController::class, 'adduser'])->name('createUser');
    Route::post('/updateUser', [AppController::class, 'updateUser'])->name('updateUser');

    Route::post('/logout', [AppController::class, 'logout'])->name('logout');
});

Route::post('/api/login', [UserController::class, 'login']);
Route::post('/api/force-reset', function (Request $request){
    Session::flush();
    return response()->json(['message' => 'Session cleared successfully']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/api/all', [MaterialController::class, 'materials']);
    Route::get('/api/materials/{id}', [MaterialController::class, 'show']);
    Route::get('/api/locations', [LocationController::class, 'locations']);
    Route::get('/api/location/{id}', [LocationController::class, 'location']);
    Route::get('/api/existingLocations', [LocationController::class, 'existingLocations']);
    Route::get('/api/users', [UserController::class, 'users']);
    Route::get('/api/profile/{id}', [UserController::class, 'profile']);

    Route::post('/api/store-locations', [LocationController::class, 'newLocations']);
    Route::post('/api/role', [UserController::class, 'role']);
    Route::post('/api/materials', [MaterialController::class, 'store']);
    Route::post('/api/create-user', [UserController::class, 'createUser']);
    Route::post('/api/update-profile/{id}', [UserController::class, 'updateProfile']);
    Route::post('/api/logout', [UserController::class, 'logout']);
    Route::post('/api/material/{id}', [MaterialController::class, 'update']);
    
    Route::put('/api/update-user', [UserController::class, 'updateUser']);
    Route::put('/api/update-location/{id}', [LocationController::class, 'updateLocation']);

    Route::delete('/api/delete-location/{id}', [LocationController::class, 'deleteLocation']);
    Route::delete('/api/delete-material/{id}', [MaterialController::class, 'delete']);

});