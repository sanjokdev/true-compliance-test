<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get("/test", function (Request $request) {
    dd("test");
});

Route::apiResource('property',\App\Http\Controllers\PropertiesController::class);
Route::get('property/{property}/certificate', [\App\Http\Controllers\PropertiesController::class,'certificates'])->name('properties.certificate');
Route::post('property/{property}/certificate', [\App\Http\Controllers\PropertiesController::class,'storeCertificate'])->name('properties.storecertificate');
Route::get('property/{property}/note', [\App\Http\Controllers\PropertiesController::class,'notes'])->name('properties.note');
Route::post('property/{property}/note', [\App\Http\Controllers\PropertiesController::class,'storeNote'])->name('properties.storenote');
Route::apiResource('certificate',\App\Http\Controllers\CertificatesController::class);
Route::get('certificate/{certificate}/note', [\App\Http\Controllers\CertificatesController::class,'notes'])->name('certificate.note');
Route::post('certificate/{certificate}/note', [\App\Http\Controllers\CertificatesController::class,'storeNote'])->name('certificate.storenote');

//MySQL raw query & eloquent query routes to get properties which has more than 5 certificates
Route::get('property/certicates/eloq', [\App\Http\Controllers\PropertiesController::class,'getPropertiesWithCertificatesMoreThan5Eloq'])->name('properties.eloq');
Route::get('property/certicates/raw', [\App\Http\Controllers\PropertiesController::class,'getPropertiesWithCertificatesMoreThan5Raw'])->name('properties.raw');
