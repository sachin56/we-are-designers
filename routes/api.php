<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\ApiemployeeController;
use App\Http\Controllers\ApiUserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Employee Routes
Route::get('/employee',[ApiemployeeController::class,'index'])->name('employee');
Route::get('/employee/create',[ApiemployeeController::class,'create']);
Route::post('/employee',[ApiemployeeController::class,'store']);
Route::get('/employee/{id}',[ApiemployeeController::class,'show']);
Route::put('/employee/{id}',[ApiemployeeController::class,'update']);
Route::delete('/employee/{id}',[EmployeeController::class,'destroy']);

//Companies Routes
Route::get('/company',[CompaniesController::class,'index'])->name('company');
Route::get('/company/create',[CompaniesController::class,'create']);
Route::post('/company',[CompaniesController::class,'store']);
Route::get('/company/{id}',[CompaniesController::class,'show']);
Route::put('/company/{id}',[CompaniesController::class,'update']);
Route::delete('/company/{id}',[CompaniesController::class,'destroy']);

//ap login
Route::post('/login',[ApiUserController::class,'login']);
