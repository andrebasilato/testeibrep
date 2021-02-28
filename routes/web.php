<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    FlightsController
};


// Route::get('/', function() {
//     return view('home');
// });

Route::get('/', [FlightsController::class, 'index'])->name('voos.index');
Route::get('/voos/somente-ida', [FlightsController::class, 'outbound'])->name('voos.outbound');
Route::get('/voos/somente-volta', [FlightsController::class, 'inbound'])->name('voos.inbound');
Route::get('/voos/ida-volta', [FlightsController::class, 'roundtrip'])->name('voos.roundtrip');
Route::get('/voos/tarifas', [FlightsController::class, 'fare'])->name('voos.fare');
Route::get('/voos/grupos', [FlightsController::class, 'groups'])->name('voos.groups');
Route::get('/voos/total', [FlightsController::class, 'total'])->name('voos.total');
Route::get('/voos', [FlightsController::class, 'show'])->name('voos.show');
Route::get('/voos/{id}', [FlightsController::class, 'details'])->name('voos.details');