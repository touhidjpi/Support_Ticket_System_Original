<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::post('/submitTicket', [App\Http\Controllers\UserController::class, 'storeTicket'])->name('submitTicket');
Route::post('/replyTicket', [App\Http\Controllers\UserController::class, 'updateTicket'])->name('replyTicket');
Route::get('/showUSRTicket', [App\Http\Controllers\UserController::class, 'detailsTicket'])->name('showUSRTicket');
Route::post('/ADMreplyTicket', [App\Http\Controllers\UserController::class, 'ADMupdateTicket'])->name('ADMreplyTicket');
Route::post('/ADMcloseTicket', [App\Http\Controllers\UserController::class, 'ADMclosedTicket'])->name('ADMcloseTicket');
