<?php

use App\Http\Controllers\CompaniesController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserRoleController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


//Employee Routes
Route::get('/employee',[EmployeeController::class,'index'])->name('employee');
Route::get('/employee/create',[EmployeeController::class,'create']);
Route::post('/employee',[EmployeeController::class,'store']);
Route::get('/employee/{id}',[EmployeeController::class,'show']);
Route::put('/employee/{id}',[EmployeeController::class,'update']);
Route::delete('/employee/{id}',[EmployeeController::class,'destroy']);

//Companies Routes
Route::get('/company',[CompaniesController::class,'index'])->name('company');
Route::get('/company/create',[CompaniesController::class,'create']);
Route::post('/company',[CompaniesController::class,'store']);
Route::get('/company/{id}',[CompaniesController::class,'show']);
Route::put('/company/{id}',[CompaniesController::class,'update']);
Route::delete('/company/{id}',[CompaniesController::class,'destroy']);

//user roles Routes
Route::get('/role',[UserRoleController::class,'index'])->name('role');
Route::get('/role/create',[UserRoleController::class,'create']);
Route::post('/role',[UserRoleController::class,'store']);
Route::get('/role/{id}',[UserRoleController::class,'show']);
Route::put('/role/{id}',[UserRoleController::class,'update']);
Route::delete('/role/{id}',[UserRoleController::class,'destroy']);

//user routes
Route::get('/user',[UserController::class,'index'])->name('user');
Route::get('/user/create',[UserController::class,'create']);
Route::post('/user',[UserController::class,'store']);
Route::get('/user/{id}',[UserController::class,'show']);
Route::put('/user/{id}',[UserController::class,'update']);
Route::delete('/user/{id}',[UserController::class,'destroy']);
