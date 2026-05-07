<?php

use App\Http\Controllers\BeneficiariesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'verified'])->group(function () {
    // Désactive l’uri /
    Route::get('/', function () {
        return to_route('transac_index');
    });

    Route::controller(TransactionsController::class)
        ->prefix('transactions')
        ->group(function () {
            Route::get('/', 'index')->name('transac_index');
            Route::post('/', 'filter')->name('transac_filter');
            Route::get('/get/{id}', 'get')->name('transac_get');
            Route::post('/store', 'store')->name('transac_store');
        });

    Route::controller(BeneficiariesController::class)
        ->prefix('benefs')
        ->group(function () {
            Route::get('/get/{id}', 'get')->name('benef_get');
            Route::post('/store', 'store')->name('benef_store');
        });

    Route::controller(CategoriesController::class)
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
