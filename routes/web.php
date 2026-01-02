<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $redirectUrl = config('app.redirect_url');
    if ($redirectUrl) {
        return redirect()->away($redirectUrl, 301);
    }
    return redirect()->route('login');
});

Auth::routes(["register" => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('projects', App\Http\Controllers\Admin\ProjectController::class);
    Route::resource('blogs', App\Http\Controllers\Admin\BlogController::class);
    Route::post('blogs/upload-image', [App\Http\Controllers\Admin\BlogController::class, 'uploadImage'])->name('blogs.uploadImage');

    Route::get('profile', [App\Http\Controllers\Admin\ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('profile', [App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('profile.update');
    Route::put('password', [App\Http\Controllers\Admin\ProfileController::class, 'updatePassword'])->name('profile.password');

    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
    Route::resource('skills', App\Http\Controllers\Admin\SkillController::class);
    Route::resource('experiences', App\Http\Controllers\Admin\ExperienceController::class);
    Route::resource('contact-messages', App\Http\Controllers\Admin\ContactMessageController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::resource('portfolio-items', App\Http\Controllers\Admin\PortfolioItemController::class);
});
