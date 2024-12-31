<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

Route::get('/', [AppController::class, 'home'])->name('home');
Route::get('/login', function () {
    return view('login');
});

Route::get('/newLocation', [AppController::class, 'getAllLocations'])->name('getAllLocations');

Route::post('/addLocation', [AppController::class, 'addLocation'])->name('addLocation');
Route::post('/newMaterial', [AppController::class, 'addMaterial'])->name('newMaterial');

Route::get('/addMaterial', [AppController::class, 'getAllMaterials'])->name(('getAllMaterials'));
Route::get('/newUser', function () {
    return view('addUser');
});
Route::get('/editUser', function () {
    return view('editUser');
});