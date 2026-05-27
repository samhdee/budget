<?php

use App\Http\Controllers\BeneficiariesController;
use App\Http\Controllers\CategoriesController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\LabelsController;
use App\Http\Controllers\RecurrencesController;
use App\Http\Controllers\TransactionsController;
use Illuminate\Support\Facades\Route;

Auth::routes(['register' => false]);

Route::middleware(['auth', 'verified'])->group(function () {
    // Désactive l’uri /
    Route::controller(DashboardController::class)
        ->prefix('dashboard')
        ->group(function () {
            Route::get('/', 'index')->name('home');
            Route::post('/expanses/filter', 'expFilter')->name('dashboard_exp_filter');
        });

    Route::controller(TransactionsController::class)
        ->prefix('transactions')
        ->group(function () {
            Route::get('/', 'index')->name('transac_index');
            Route::post('/', 'filter')->name('transac_filter');
            Route::get('/get/{id}', 'get')->name('transac_get');
            Route::post('/bulk-store', 'bulkStore')->name('transac_bulk_store');
            Route::post('/bulk-delete', 'bulkDelete')->name('transac_bulk_delete');
            Route::post('/store', 'store')->name('transac_store');
            Route::get('/delete/{id}', 'delete')->name('transac_delete');
        });

    Route::controller(BeneficiariesController::class)
        ->prefix('benefs')
        ->group(function () {
            Route::get('/', 'index')->name('benef_index');
            Route::get('/get/{id}', 'get')->name('benef_get');
            Route::post('/filter', 'filter')->name('benef_filter');
            Route::post('/store', 'store')->name('benef_store');
            Route::post('/bulk-store', 'storeInBulk')->name('benef_bulk_store');
            Route::post('/sync', 'syncCategories')->name('benef_sync');
            Route::get('/delete/{benef_id}', 'delete')->name('benef_delete');
        });

    Route::controller(CategoriesController::class)
        ->prefix('categories')
        ->group(function () {
            Route::get('/', 'index')->name('categ_index');
            Route::get('/get/{id}', 'get')->name('categ_get');
            Route::post('/store', 'store')->name('categ_store');
            Route::post('/delete', 'delete')->name('categ_delete');
        });

    Route::controller(LabelsController::class)
        ->prefix('labels')
        ->group(function () {
            Route::get('/', 'index')->name('labels_index');
            Route::get('/get/{id}', 'get')->name('label_get');
            Route::post('/store', 'store')->name('label_store');
            Route::post('/delete', 'delete')->name('label_delete');
        });

    Route::controller(ImportController::class)
        ->prefix('import')
        ->group(function () {
            Route::get('/', 'index')->name('import_index');
            Route::post('/store', 'store')->name('import_store');
        });

    Route::controller(RecurrencesController::class)
        ->prefix('recurrences')
        ->group(function () {
            Route::get('/', 'index')->name('recurrences_index');
            Route::get('/filter', 'filter')->name('recurrences_filter');
            Route::get('/get/{id}', 'get')->name('recurrences_get');
            Route::post('/store', 'store')->name('recurrences_store');
            Route::get('/toggle-active/{recurrence_id}', 'toggleActive')->name('recurrences_toggle_active');
            Route::get('/add-transacs/{recurrence_id}', 'addTransacs')->name('recurrences_add_transacs');
            Route::get('/detect', 'detectRecurrences')->name('recurrences_detect');
        });
});
