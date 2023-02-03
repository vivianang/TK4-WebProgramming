<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\StaffsController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\ItemsController;
use App\Http\Controllers\TransactionsController;

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

// Login and Logout
Route::get('/auth/login', [AuthController::class, 'login'])->middleware('guest')->name('login');
Route::post('/auth/login', [AuthController::class, 'submitLogin'])->middleware('guest')->name('login.submit');
Route::get('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/auth/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Staff
Route::get('/staff', [StaffsController::class, 'index'])->name('staffs.index')->middleware('auth');
Route::get('/staff/add', [StaffsController::class, 'add'])->name('staffs.add')->middleware('auth');
Route::post('/staff/add', [StaffsController::class, 'submitAdd'])->name('staffs.add.submit')->middleware('auth');
Route::get('/staff/{id}/edit', [StaffsController::class, 'edit'])->name('staffs.edit')->middleware('auth');
Route::post('/staff/{id}/edit', [StaffsController::class, 'submitEdit'])->name('staffs.edit.submit')->middleware('auth');
Route::get('/staff/{id}/delete', [StaffsController::class, 'delete'])->name('staffs.delete')->middleware('auth');

// Customer
Route::get('/customer', [CustomersController::class, 'index'])->name('customers.index')->middleware('auth');
Route::get('/customer/add', [CustomersController::class, 'add'])->name('customers.add')->middleware('auth');
Route::post('/customer/add', [CustomersController::class, 'submitAdd'])->name('customers.add.submit')->middleware('auth');
Route::get('/customer/{id}/edit', [CustomersController::class, 'edit'])->name('customers.edit')->middleware('auth');
Route::post('/customer/{id}/edit', [CustomersController::class, 'submitEdit'])->name('customers.edit.submit')->middleware('auth');
Route::get('/customer/{id}/delete', [CustomersController::class, 'delete'])->name('customers.delete')->middleware('auth');

// Item
Route::get('/item', [ItemsController::class, 'index'])->name('items.index')->middleware('auth');
Route::get('/item/add', [ItemsController::class, 'add'])->name('items.add')->middleware('auth');
Route::post('/item/add', [ItemsController::class, 'submitAdd'])->name('items.add.submit')->middleware('auth');
Route::get('/item/{id}/edit', [ItemsController::class, 'edit'])->name('items.edit')->middleware('auth');
Route::post('/item/{id}/edit', [ItemsController::class, 'submitEdit'])->name('items.edit.submit')->middleware('auth');
Route::get('/item/{id}/delete', [ItemsController::class, 'delete'])->name('items.delete')->middleware('auth');

// Item
Route::get('/transaction', [TransactionsController::class, 'index'])->name('transactions.index')->middleware('auth');
Route::get('/transaction/pending', [TransactionsController::class, 'indexPending'])->name('transactions.index.pending')->middleware('auth');
Route::get('/transaction/add', [TransactionsController::class, 'add'])->name('transactions.add')->middleware('auth');
Route::post('/transaction/add', [TransactionsController::class, 'submitAdd'])->name('transactions.add.submit')->middleware('auth');
Route::get('/transaction/{id}/view', [TransactionsController::class, 'view'])->name('transactions.view')->middleware('auth');
Route::get('/transaction/{id}/revoke', [TransactionsController::class, 'delete'])->name('transactions.delete')->middleware('auth');
Route::get('/transaction/{id}/approve', [TransactionsController::class, 'approve'])->name('transactions.delete')->middleware('auth');


