<?php

use App\Http\Controllers\BeneficiariesController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'verified'])->group(function () {
    Route::controller(TransactionsController::class)
        ->prefix('transactions')
        ->group(function () {
            Route::get('/', 'index')->name('home');
            Route::get('/get/{id}', 'get')->name('transac_get');
            Route::post('/filter', 'filter')->name('transac_filter');
            Route::post('/store', 'store')->name('transac_store');
        });

    Route::controller(BeneficiariesController::class)
        ->prefix('benefs')
        ->group(function () {
            Route::get('/get/{id}', 'get')->name('benef_get');
            Route::post('/store', 'store')->name('benef_store');
        });

    Route::controller(ImportController::class)
        ->prefix('categories')
        ->group(function () {
            Route::get('/', 'index')->name('categ_index');
            Route::get('/form', 'form')->name('categ_form');
            Route::post('/store', 'store')->name('categ_store');
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
        });
});
