<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

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

    Route::post('/updateMaterial/{id}', [AppController::class, 'updateMaterial'])->name('updateMaterial');
    Route::post('/updateLocation/{id}', [AppController::class, 'updateLocation'])->name('updateLocation');
    Route::post('/addLocation', [AppController::class, 'addLocation'])->name('addLocation');
    Route::post('/newMaterial', [AppController::class, 'addMaterial'])->name('newMaterial');
    Route::post('/createUser', [AppController::class, 'adduser'])->name('createUser');
    Route::post('/updateUser', [AppController::class, 'updateUser'])->name('updateUser');

    Route::post('/logout', [AppController::class, 'logout'])->name('logout');
});