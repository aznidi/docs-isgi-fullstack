<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Auth\GoogleAuthController;
use App\Http\Controllers\StatisticsController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\SolutionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Ce fichier est utilisé pour définir les routes de l'API. Chaque route est
| assignée à un middleware pour s'assurer que seules les requêtes autorisées
| peuvent y accéder.
|
*/

/**
 * Authentification via Google OAuth
 */
Route::get('/auth/google/redirect', [GoogleAuthController::class, 'redirectToGoogle']);
Route::get('/auth/google/callback', [GoogleAuthController::class, 'handleGoogleCallback']);

/**
 * Récupération des informations de l'utilisateur authentifié
 */
Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * Routes pour la gestion du profil utilisateur
 * - Mise à jour du profil (nom, email, mot de passe, avatar)
 * - Suppression de compte
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::put('/update-username', [ProfileController::class, 'updateUsername']);
    Route::put('/update-email', [ProfileController::class, 'updateEmail']);
    Route::put('/update-password', [ProfileController::class, 'updatePassword']);
    Route::delete('/delete-account', [ProfileController::class, 'destroy']);
    Route::post('/update-avatar', [ProfileController::class, 'updateAvatar']);
});

/**
 * Routes pour l'administration (requiert le rôle admin)
 * - Gestion des utilisateurs (CRUD)
 * - Tableau de bord pour les statistiques et la gestion
 */
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard']);
    Route::get('/admin/users', [AdminController::class, 'users']);
    Route::get('/admin/user/{id}', [AdminController::class, 'user']);
    Route::post('/admin/user', [AdminController::class, 'createUser']);
    Route::put('/admin/user/{id}', [AdminController::class, 'updateUser']);
    Route::delete('/admin/user/{id}', [AdminController::class, 'deleteUser']);
});

/**
 * Routes pour la gestion des documents par l'admin (CRUD)
 */
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::get('/admin/documents', [DocumentController::class, 'index']);
    Route::post('/admin/documents', [DocumentController::class, 'store']);
    Route::get('/admin/documents/{id}', [DocumentController::class, 'show']);
    Route::put('/admin/documents/{id}', [DocumentController::class, 'update']);
    Route::delete('/admin/documents/{id}', [DocumentController::class, 'destroy']);
});


/**
 * Routes pour documents
 */

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/documents', [DocumentController::class, 'index']); // Liste des documents
    Route::get('/documents/{id}', [DocumentController::class, 'show']); // Détail d'un document
    Route::get('/documents/filter', [DocumentController::class, 'filter']); // Filtrage des documents
});


/**
 * Routes pour la gestion des modules
 * - CRUD des modules
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/modules', [ModuleController::class, 'index']); // Liste des modules
    Route::post('/modules', [ModuleController::class, 'store']); // Ajouter un module
    Route::get('/modules/{id}', [ModuleController::class, 'show']); // Récupérer un module spécifique
    Route::put('/modules/{id}', [ModuleController::class, 'update']); // Mettre à jour un module
    Route::delete('/modules/{id}', [ModuleController::class, 'destroy']); // Supprimer un module

    // Nouvelle route pour rechercher des modules
    Route::get('/modules/search', [ModuleController::class, 'search']); // Recherche de modules paginée// Recherche de modules
});

/**
 * Routes pour la gestion des documents
 * - Ajouter un commentaire, signaler, aimer, etc.
 */
Route::middleware(['auth:sanctum'])->group(function () {
    Route::prefix('documents')->group(function () {
        Route::get('/{id}/', [DocumentController::class, 'show']); // Récupérer un document
        Route::post('/{id}/comments', [DocumentController::class, 'addComment']); // Ajouter un commentaire
        Route::get('/{id}/comments', [DocumentController::class, 'getComments']); // Récupérer les commentaires
        Route::put('/comments/{comment}', [DocumentController::class, 'updateComment']); // Modifier un commentaire
        Route::delete('/comments/{comment}', [DocumentController::class, 'deleteComment']); // Supprimer un commentaire
        Route::post('/{id}/report', [DocumentController::class, 'reportDocument']); // Signaler un document
        Route::get('/{id}/similar', [DocumentController::class, 'getSimilarDocuments']); // Documents similaires

        // Gestion des likes
        Route::get('/{id}/likes', [DocumentController::class, 'getTotalLikes']); // Nombre total de likes
        Route::post('/{id}/like', [DocumentController::class, 'toggleLike']); // Ajouter/retirer un like
        Route::get('/{id}/like-status', [DocumentController::class, 'getLikeStatus']); // Vérifier l'état du like

        // Gestion des favoris
        Route::post('/{id}/favorite', [FavoriteController::class, 'toggleFavorite']); // Ajouter/retirer des favoris
        Route::get('/{id}/is-favorite', [FavoriteController::class, 'isFavorite']); // Vérifier si un document est en favori
    });

    // Gestion des favoris utilisateur
    Route::prefix('favorites')->group(function () {
        Route::get('/', [FavoriteController::class, 'getUserFavorites']); // Liste des documents favoris
    });

    // Recherche de documents dans un module
    Route::get('/modules/{moduleId}/documents/search', [DocumentController::class, 'searchDocuments']);
});

/**
 * Routes pour les statistiques
 * - Récupérer diverses statistiques sur les modules, documents, utilisateurs, etc.
 */
Route::middleware(['auth:sanctum', 'admin'])->prefix('statistics')->group(function () {
    Route::get('/modules-count', [StatisticsController::class, 'modulesCount']); // Nombre de modules
    Route::get('/documents-count', [StatisticsController::class, 'documentsCount']); // Nombre de documents
    Route::get('/users-count', [StatisticsController::class, 'usersCount']); // Nombre d'utilisateurs
    Route::get('/favorites-count', [StatisticsController::class, 'favoritesCount']); // Nombre de favoris
    Route::get('/comments-count', [StatisticsController::class, 'commentsCount']); // Nombre de commentaires
    Route::get('/reports-count', [StatisticsController::class, 'reportsCount']); // Nombre de signalements
    Route::get('/general', [StatisticsController::class, 'generalStatistics']); // Statistiques générales
    Route::get('/top-modules', [StatisticsController::class, 'topModules']); // Top 5 modules
    Route::get('/top-downloaded-documents', [StatisticsController::class, 'topDownloadedDocuments']); // Top 5 documents téléchargés
    Route::get('/top-liked-documents', [StatisticsController::class, 'topLikedDocuments']); // Top 5 documents aimés
});

/**
 * Routes pour les statistiques (Public)
 * - Récupérer diverses statistiques sur les modules, documents, utilisateurs, etc.
 */
Route::prefix('public')->group(function () {
    Route::get('/statistics/modules-count', [StatisticsController::class, 'modulesCount']); // Nombre de modules
    Route::get('/statistics/documents-count', [StatisticsController::class, 'documentsCount']); // Nombre de documents
    Route::get('/statistics/general', [StatisticsController::class, 'generalStatistics']); // Statistiques générales
    Route::get('/statistics/users-count', [StatisticsController::class, 'usersCount']); // Nombre d'utilisateur
    Route::get('/statistics/top-liked-documents', [StatisticsController::class, 'topLikedDocuments']); // Top 5 documents aimés
    Route::get('/statistics/top-downloaded-documents', [StatisticsController::class, 'topDownloadedDocuments']); // Top 5 documents téléchargés
});

/**
 * Routes pour les exercices
 * - Récupérer les exercices.
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/exercises', [ExerciseController::class, 'index']); // Liste des exercices
    Route::get('/exercises/{id}', [ExerciseController::class, 'show']); // Détails d'un exercice
    Route::post('/exercises', [ExerciseController::class, 'store']); // Créer un exercice
    Route::put('/exercises/{id}', [ExerciseController::class, 'update']);
    Route::delete('/exercises/{id}', [ExerciseController::class, 'destroy']); // Supprimer un exercice
});

/**
 * Routes pour les Solutions
 * - Récupérer les exercices.
 */
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/exercises/{exerciseId}/solutions', [SolutionController::class, 'index']); // Liste des solutions d'un exercice
    Route::post('/exercises/{exerciseId}/solution', [SolutionController::class, 'store']); // Ajouter une solution
    Route::put('/solutions/{id}', [SolutionController::class, 'update']); // Mettre à jour une solution
    Route::delete('/solutions/{id}', [SolutionController::class, 'destroy']); // Supprimer une solution
});
