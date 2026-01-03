<?php

use App\Http\Controllers\Api\ServiceRequestController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes Publiques
|--------------------------------------------------------------------------
*/

// Demande de service depuis le site vitrine (PUBLIC)
Route::post('/service-requests', [ServiceRequestController::class, 'store'])
    ->name('api.service-requests.store');

// Vérifier le statut d'une demande (PUBLIC)
Route::post('/service-requests/status', [ServiceRequestController::class, 'status'])
    ->name('api.service-requests.status');
