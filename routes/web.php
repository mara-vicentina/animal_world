<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AnimalsController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\AppointmentsController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// Auth

Route::get('/', [AuthenticatedSessionController::class, 'create'])
->name('home')
->middleware('guest');

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');


// Dashboard

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


// User

Route::get('/user', [UsersController::class, 'create'])
    ->name('user.create')
    ->middleware('guest');

Route::post('/user', [UsersController::class, 'store'])
    ->name('user.store')
    ->middleware('guest');


// Clients

Route::get('clients', [ClientsController::class, 'index'])
    ->name('clients')
    ->middleware('auth');

Route::post('clients', [ClientsController::class, 'store'])
    ->name('clients.store')
    ->middleware('auth');

Route::put('clients', [ClientsController::class, 'update'])
    ->name('clients.update')
    ->middleware('auth');

Route::delete('clients', [ClientsController::class, 'destroy'])
    ->name('clients.destroy')
    ->middleware('auth');


// Animals

Route::get('animals', [AnimalsController::class, 'index'])
    ->name('animals')
    ->middleware('auth');

Route::post('animals', [AnimalsController::class, 'store'])
    ->name('animals.store')
    ->middleware('auth');

Route::put('animals', [AnimalsController::class, 'update'])
    ->name('animals.update')
    ->middleware('auth');

Route::delete('animals', [AnimalsController::class, 'destroy'])
    ->name('animals.destroy')
    ->middleware('auth');


// Appointments

Route::get('appointments', [AppointmentsController::class, 'index'])
    ->name('appointments')
    ->middleware('auth');
    
Route::post('appointments', [AppointmentsController::class, 'store'])
    ->name('appointments.store')
    ->middleware('auth');

Route::put('appointments', [AppointmentsController::class, 'update'])
    ->name('appointments.update')
    ->middleware('auth');

Route::delete('appointments', [AppointmentsController::class, 'destroy'])
    ->name('appointments.destroy')
    ->middleware('auth');
    

// Financial

Route::get('financial', [FinancialController::class, 'index'])
    ->name('financial')
    ->middleware('auth');

Route::post('financial', [FinancialController::class, 'store'])
    ->name('financial.store')
    ->middleware('auth');

Route::put('financial', [FinancialController::class, 'update'])
    ->name('financial.update')
    ->middleware('auth');

Route::delete('financial', [FinancialController::class, 'destroy'])
    ->name('financial.destroy')
    ->middleware('auth');

// Schedule

Route::get('schedule', [ScheduleController::class, 'index'])
->name('schedule')
->middleware('auth');

Route::post('schedule', [ScheduleController::class, 'store'])
    ->name('schedule.store')
    ->middleware('auth');

Route::put('schedule', [ScheduleController::class, 'update'])
    ->name('schedule.update')
    ->middleware('auth');

Route::delete('schedule', [ScheduleController::class, 'destroy'])
    ->name('schedule.destroy')
    ->middleware('auth');