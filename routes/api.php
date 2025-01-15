<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Auth\GoogleAuthController;


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


Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);


Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});


Route::middleware('auth:sanctum')->group(function () {
    Route::put('/update-username', [ProfileController::class, 'updateUsername']);
    Route::put('/update-email', [ProfileController::class, 'updateEmail']);
    Route::put('/update-password', [ProfileController::class, 'updatePassword']);
    Route::delete('/delete-account', [ProfileController::class, 'destroy']);
    Route::post('/update-avatar', [ProfileController::class, 'updateAvatar']);
});



Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/user/{id}', [AdminController::class, 'user']);
    Route::post('/admin/user', [AdminController::class, 'createUser']);
    Route::put('/admin/user/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser']);
});

Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/documents', [DocumentController::class, 'index']);
    Route::post('/admin/documents', [DocumentController::class, 'store']);
    Route::get('/admin/documents/{id}', [DocumentController::class, 'show']);
    Route::put('/admin/documents/{id}', [DocumentController::class, 'update']);
    Route::delete('/admin/documents/{id}', [DocumentController::class, 'destroy']);
});




Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/modules', [ModuleController::class, 'index']); // Liste des modules
    Route::post('/modules', [ModuleController::class, 'store']); // Ajouter un module
    Route::get('/modules/{id}', [ModuleController::class, 'show']); // Récupérer un module
    Route::put('/modules/{id}', [ModuleController::class, 'update']); // Mettre à jour un module
    Route::delete('/modules/{id}', [ModuleController::class, 'destroy']); // Supprimer un module
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/modules/{moduleId}/documents/search', [DocumentController::class, 'searchDocuments']);
    Route::get('/documents/{id}/', [DocumentController::class, 'show']);
    Route::post('/documents/{id}/comments', [DocumentController::class, 'addComment']);
    Route::get('/documents/{id}/comments', [DocumentController::class, 'getComments']);
    Route::post('/documents/{id}/report', [DocumentController::class, 'reportDocument']);
    Route::get('/documents/{id}/similar', [DocumentController::class, 'getSimilarDocuments']);

    // Routes pour les likes
    Route::get('/documents/{id}/likes', [DocumentController::class, 'getTotalLikes']);
    Route::post('/documents/{id}/like', [DocumentController::class, 'toggleLike']);
    Route::get('/documents/{id}/like-status', [DocumentController::class, 'getLikeStatus']);

});

