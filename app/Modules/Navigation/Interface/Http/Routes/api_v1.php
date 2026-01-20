<?php

use App\Modules\Navigation\Interface\Http\Controllers\NavigationController;
use Illuminate\Support\Facades\Route;

Route::prefix('v1')->group(function () {



    Route::middleware(['auth:api'])->group(function () {
        Route::get('/navigation', [NavigationController::class, 'getUserMenu']);
    });
});
