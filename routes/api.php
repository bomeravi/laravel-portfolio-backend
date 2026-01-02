<?php

use App\Http\Controllers\Api\V1\Admin\DiaryController;
use App\Http\Controllers\Api\V1\ContactMessageController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BlogController;
use App\Http\Controllers\Api\V1\SkillController;
use App\Http\Controllers\Api\V1\ProjectController;
use App\Http\Controllers\Api\V1\PortfolioController;
use App\Http\Controllers\Api\V1\ExperienceController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('v1/me', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::post('v1/me', function (Request $request) {
    return $request->user();
})->middleware('auth:api','demo.user');

Route::prefix('v1')
    ->as('v1.')
    ->middleware(['auth:api'])
    ->group(function () {
        Route::get('profile', function (Request $request) {
            return $request->user();
        })->name('user.profile');
        Route::post('profile', function (Request $request) {
            return $request->user();
        })->name('user.profile.update');
    });

Route::post('v1/login', [AuthController::class, 'login']);
Route::post('v1/logout', [AuthController::class, 'logout'])->middleware('auth:api');
Route::post('v1/refresh', [AuthController::class, 'refreshToken']);
Route::get('v1/skills', [SkillController::class, 'index']);
Route::get('v1/skills/list', [SkillController::class, 'list']);
Route::get('v1/projects', [ProjectController::class, 'index']);
Route::get('v1/projects/list', [ProjectController::class, 'list']);
Route::get('v1/experiences', [ExperienceController::class, 'index']);
Route::get('v1/experiences/list', [ExperienceController::class, 'list']);
Route::get('v1/portfolios', [PortfolioController::class, 'index']);
Route::get('v1/portfolios/list', [PortfolioController::class, 'list']);
Route::get('v1/blogs', [BlogController::class, 'index']);
Route::get('v1/blogs/list', [BlogController::class, 'list']);
Route::get('v1/blogs/{slug}', [BlogController::class, 'show']);
Route::get('v1/blogs/category/{categorySlug}', [BlogController::class, 'byCategory']);
Route::get('v1//blogs/tag/{tagSlug}', [BlogController::class, 'byTag']);

Route::prefix('v1')->group(function () {
    Route::post('/presend-contact', [ContactMessageController::class, 'presend']);
    Route::post('/contact', [ContactMessageController::class, 'store']);
});

Route::prefix('v1/admin')
    ->as('v1.admin.')
    ->middleware(['auth:api','demo.user'])
    ->namespace('App\Http\Controllers\Api\V1\Admin')
    ->group(function () {
        Route::apiResource('blogs', 'BlogController');
        Route::apiResource('categories', 'BlogCategoryController');
        Route::apiResource('diaries', DiaryController::class);
    });
