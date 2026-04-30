<?php

use App\Http\Controllers\ImportController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', function () {
        return view('app');
    })->name('home');

    Route::controller(TransactionsController::class)
        ->prefix('transactions')
        ->group(function () {
            Route::get('/', 'index')->name('transac_index');
            Route::post('/filter', 'filter')->name('transac_filter');
            Route::get('/form', 'form')->name('transac_form');
            Route::post('/store', 'store')->name('transac_store');
            Route::post('/upload', 'upload')->name('transac_upload');
            Route::post('/update', 'update')->name('transac_update');
        });

    Route::controller(ImportController::class)
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

    Route::controller(ImportController::class)
        ->prefix('import')
        ->group(function () {
            Route::get('/', 'index')->name('import_index');
            Route::post('/store', 'store')->name('import_store');
            Route::post('/update', 'update')->name('import_update');
        });
});
