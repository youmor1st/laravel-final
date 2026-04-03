<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

// Перенаправление с главной страницы на логин
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth маршруты (Участник 1)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// TODO (Участник 2): добавить маршруты admin.dashboard, admin.users.*, admin.classes.*, admin.students.*, admin.teachers.*
// TODO (Участник 3): добавить маршруты admin.rules.*, admin.points.*, admin.history.*
// TODO (Участник 4): добавить маршруты teacher.*, student.*, notifications.*
