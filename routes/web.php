<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UsersController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/verify-phone', function () {
    return Inertia::render('Auth/VerifyPhone');
})->middleware(['auth'])->name('verification.phone');


Route::middleware('admin')->group(function () {
    Route::get('/dashboard', function () {
        $user = Auth::user();
        return Inertia::render('Dashboard',[
                    'user' => $user
            ]);
    })->name('dashboard');
    Route::get('/users', [UsersController::class, 'users'])->name('dashboard.users');
    Route::get('/user/show/{id}', [UsersController::class, 'show'])->name('user.show');
    Route::get('/user/edit/{id}', [UsersController::class, 'edit'])->name('user.edit');
    Route::patch('/user/update/{id}', [UsersController::class, 'update'])->name('user.update');
    Route::delete('/user/destroy/{id}', [UsersController::class, 'destroy'])->name('user.destroy');
    Route::put('/user/password/{id}', [UsersController::class, 'updatePassword'])->name('user.password');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


require __DIR__.'/auth.php';
