<?php

namespace App\Http\Controllers;

use App\Models\Module;
use App\Models\Document;
use App\Models\User;
use App\Models\Favorite;
use App\Models\Comment;
use App\Models\Report;
use Illuminate\Http\Request;

class StatisticsController extends Controller
{
    /**
     * Récupérer le nombre total de modules.
     */
    public function modulesCount()
    {
        try {
            $count = Module::count(); // Nombre total de modules
            return response()->json(['modulesCount' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du nombre de modules', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer le nombre total de documents.
     */
    public function documentsCount()
    {
        try {
            $count = Document::count(); // Nombre total de documents
            return response()->json(['documentsCount' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du nombre de documents', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer le nombre total d'utilisateurs.
     */
    public function usersCount()
    {
        try {
            $count = User::where('role', 'user')->count(); // Nombre total d'utilisateurs
            return response()->json(['usersCount' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du nombre d\'utilisateurs', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer le nombre total de documents favoris.
     */
    public function favoritesCount()
    {
        try {
            $count = Favorite::count(); // Nombre total de favoris
            return response()->json(['favoritesCount' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du nombre de favoris', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer le nombre total de commentaires.
     */
    public function commentsCount()
    {
        try {
            $count = Comment::count(); // Nombre total de commentaires
            return response()->json(['commentsCount' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du nombre de commentaires', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer le nombre total de signalements.
     */
    public function reportsCount()
    {
        try {
            $count = Report::count(); // Nombre total de signalements
            return response()->json(['reportsCount' => $count]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération du nombre de signalements', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer les statistiques générales.
     */
    public function generalStatistics()
    {
        try {
            $statistics = [
                'modulesCount' => Module::count(),
                'documentsCount' => Document::count(),
                'usersCount' => User::count(),
                'favoritesCount' => Favorite::count(),
                'commentsCount' => Comment::count(),
                'reportsCount' => Report::count(),
                'totalDownloads' => Document::sum('nbTelechargements'), // Téléchargements totaux
                'totalLikes' => Document::sum('likes'), // Total des likes sur les documents
                'totalReports' => Document::sum('reports') // Total des signalements
            ];
            return response()->json($statistics);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des statistiques générales', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer les 5 modules les plus populaires basés sur le nombre de documents.
     */
    public function topModules()
    {
        try {
            $modules = Module::withCount('documents')
                ->orderBy('documents_count', 'desc')
                ->take(3)
                ->get();

            return response()->json(['topModules' => $modules]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des modules les plus populaires', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer les 5 documents les plus téléchargés.
     */
    public function topDownloadedDocuments()
    {
        try {
            $documents = Document::with('module')
                ->orderBy('nbTelechargements', 'desc')
                ->take(3)
                ->get();

            return response()->json(['topDownloadedDocuments' => $documents]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des documents les plus téléchargés', 'message' => $e->getMessage()], 500);
        }
    }

    /**
     * Récupérer les 5 documents les plus aimés.
     */
    public function topLikedDocuments()
    {
        try {
            $documents = Document::with('module')
                ->orderBy('likes', 'desc')
                ->take(3)
                ->get();

            return response()->json(['topLikedDocuments' => $documents]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur lors de la récupération des documents les plus aimés', 'message' => $e->getMessage()], 500);
        }
    }
}
