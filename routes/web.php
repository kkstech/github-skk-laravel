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
    Route::get('/{certificate}', [CertificateController::class, 'show'])->name('show');
    Route::put('/{certificate}', [CertificateController::class, 'update'])->name('update');
    Route::delete('/{certificate}', [CertificateController::class, 'destroy'])->name('destroy');
});
