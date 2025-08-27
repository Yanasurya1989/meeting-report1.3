<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DivisiController;
use App\Http\Controllers\SubDivisiController;
use App\Http\Controllers\MeetingReportController;

Route::get('/', function () {
    return redirect('/login');
});


Route::get('/login', [\App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);

Route::get('/register', [\App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [\App\Http\Controllers\AuthController::class, 'register']);

Route::post('/logout', [\App\Http\Controllers\AuthController::class, 'logout'])->name('logout');


Route::middleware('auth')->get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');


Route::middleware('auth')->group(function () {
    Route::get('/meeting/create', [MeetingReportController::class, 'create'])->name('meeting.create');
    Route::post('/meeting/store', [MeetingReportController::class, 'store'])->name('meeting.store');
    Route::get('/meeting/{id}/edit', [MeetingReportController::class, 'edit'])->name('meeting.edit');
    Route::put('/meeting/{id}', [MeetingReportController::class, 'update'])->name('meeting.update');
    Route::delete('/meeting/{id}', [MeetingReportController::class, 'destroy'])->name('meeting.destroy');
    Route::get('/meeting', [MeetingReportController::class, 'index'])->name('meeting.index');
    Route::get('/meeting/export', [MeetingReportController::class, 'export'])->name('meeting.export');

    // Divisi & Sub divisi
    Route::get('/divisi/list', [DivisiController::class, 'getDivisi'])->name('divisi.list');
    Route::get('/divisi/{id}/subdivisi', [DivisiController::class, 'getSubDivisi'])->name('divisi.subdivisi');
    Route::get('/subdivisi/{id}/peserta', [DivisiController::class, 'getPeserta'])->name('subdivisi.peserta');
    Route::resource('divisi', DivisiController::class);
    Route::resource('subdivisi', SubDivisiController::class);

    // User
    Route::resource('users', UserController::class);
    Route::resource('users', UserController::class)->only(['index']);
});
