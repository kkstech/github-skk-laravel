<?php

use App\Http\Controllers\CertificateController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [CertificateController::class, 'index'])->name('certificates.index');

Route::prefix('certificates')->name('certificates.')->group(function () {
    Route::post('/', [CertificateController::class, 'store'])->name('store');
    Route::get('/{certificate:nomor_registrasi}', [CertificateController::class, 'show'])->name('show');
    Route::put('/{certificate}', [CertificateController::class, 'update'])->name('update');
    Route::delete('/{certificate}', [CertificateController::class, 'destroy'])->name('destroy');
});

use App\Http\Controllers\MasterDataController;

Route::get('/master-data', [MasterDataController::class, 'index'])->name('master.index');

Route::prefix('api/master')->name('api.master.')->group(function () {
    // Classifications
    Route::get('/classifications', [MasterDataController::class, 'getClassifications'])->name('classifications.index');
    Route::post('/classifications', [MasterDataController::class, 'storeClassification'])->name('classifications.store');
    Route::put('/classifications/{classification}', [MasterDataController::class, 'updateClassification'])->name('classifications.update');
    Route::delete('/classifications/{classification}', [MasterDataController::class, 'destroyClassification'])->name('classifications.destroy');

    // Subclassifications
    Route::get('/subclassifications', [MasterDataController::class, 'getSubclassifications'])->name('subclassifications.index');
    Route::post('/subclassifications', [MasterDataController::class, 'storeSubclassification'])->name('subclassifications.store');
    Route::put('/subclassifications/{subclassification}', [MasterDataController::class, 'updateSubclassification'])->name('subclassifications.update');
    Route::delete('/subclassifications/{subclassification}', [MasterDataController::class, 'destroySubclassification'])->name('subclassifications.destroy');

    // Qualifications
    Route::get('/qualifications', [MasterDataController::class, 'getQualifications'])->name('qualifications.index');
    Route::post('/qualifications', [MasterDataController::class, 'storeQualification'])->name('qualifications.store');
    Route::put('/qualifications/{qualification}', [MasterDataController::class, 'updateQualification'])->name('qualifications.update');
    Route::delete('/qualifications/{qualification}', [MasterDataController::class, 'destroyQualification'])->name('qualifications.destroy');

    // Work Positions
    Route::get('/work-positions', [MasterDataController::class, 'getWorkPositions'])->name('work-positions.index');
    Route::post('/work-positions', [MasterDataController::class, 'storeWorkPosition'])->name('work-positions.store');
    Route::put('/work-positions/{workPosition}', [MasterDataController::class, 'updateWorkPosition'])->name('work-positions.update');
    Route::delete('/work-positions/{workPosition}', [MasterDataController::class, 'destroyWorkPosition'])->name('work-positions.destroy');

    // Lsps
    Route::get('/lsps', [MasterDataController::class, 'getLsps'])->name('lsps.index');
    Route::post('/lsps', [MasterDataController::class, 'storeLsp'])->name('lsps.store');
    Route::put('/lsps/{lsp}', [MasterDataController::class, 'updateLsp'])->name('lsps.update');
    Route::delete('/lsps/{lsp}', [MasterDataController::class, 'destroyLsp'])->name('lsps.destroy');

    // Associations
    Route::get('/associations', [MasterDataController::class, 'getAssociations'])->name('associations.index');
    Route::post('/associations', [MasterDataController::class, 'storeAssociation'])->name('associations.store');
    Route::put('/associations/{association}', [MasterDataController::class, 'updateAssociation'])->name('associations.update');
    Route::delete('/associations/{association}', [MasterDataController::class, 'destroyAssociation'])->name('associations.destroy');
});
