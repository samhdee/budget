<?php

use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('app');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(TransactionsController::class)
        ->prefix('transactions')
        ->group(function () {
            Route::get('/', 'index')->name('transac_index');
            Route::get('/form', 'form')->name('transac_form');
            Route::post('/store', 'store')->name('transac_store');
            Route::post('/upload', 'upload')->name('transac_upload');
            Route::post('/update', 'update')->name('transac_update');
        });

    Route::controller(CategoriesController::class)
        ->prefix('categories')
        ->group(function () {
            Route::get('/', 'index')->name('categ_index');
            Route::get('/form', 'form')->name('categ_form');
            Route::post('/store', 'store')->name('categ_store');
            Route::post('/update', 'update')->name('categ_update');
        });

    Route::controller(LabelsController::class)
        ->prefix('labels')
        ->group(function () {
            Route::get('/', 'index')->name('labels_index');
            Route::get('/form', 'form')->name('label_form');
            Route::post('/store', 'store')->name('labels_store');
            Route::post('/update', 'update')->name('labels_update');
        });
});
