<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\JobController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group(['middleware' => 'api'], function () {
    Route::post('login', [AuthController::class, 'login']);
    Route::post('create-job-application', [JobController::class, 'createOrUpdateJobApplication']);

    Route::group(['middleware' => 'auth.jwt'], function () {
        Route::get('view-job-application/{id?}', [JobController::class, 'viewJobApplication']);
        Route::put('update-job-application', [JobController::class, 'createOrUpdateJobApplication']);
        Route::delete('delete-job-application/{id}', [JobController::class, 'deleteJobApplication']);
        Route::post('logout', [AuthController::class, 'logout']);
    });
});
