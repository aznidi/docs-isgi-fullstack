<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Favorite;
use App\Models\Document;

class FavoriteController extends Controller
{
    // Ajouter ou supprimer un favori
    public function toggleFavorite($id)
    {
        $user = auth()->user();

        // Vérifier si le favori existe déjà
        $favorite = Favorite::where('user_id', $user->id)
            ->where('document_id', $id)
            ->first();

        if ($favorite) {
            $favorite->delete(); // Supprimer si déjà favori
            return response()->json(['message' => 'Favori supprimé', 'status' => 'removed']);
        }

        // Ajouter comme favori
        Favorite::create([
            'user_id' => $user->id,
            'document_id' => $id,
        ]);

        return response()->json(['message' => 'Favori ajouté', 'status' => 'added']);
    }

    // Vérifier si un document est favori
    public function isFavorite($id)
    {
        $user = auth()->user();
        $isFavorite = Favorite::where('user_id', $user->id)
            ->where('document_id', $id)
            ->exists();

        return response()->json(['isFavorite' => $isFavorite]);
    }

    // Récupérer tous les favoris de l'utilisateur
    public function getUserFavorites()
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non authentifié'], 401);
        }

        $favorites = Favorite::where('user_id', $user->id)
            ->whereHas('document') // Vérifie que le document existe
            ->with('document.module') // Charge les modules associés
            ->get();

        // Débogage
        if ($favorites->isEmpty()) {
            return response()->json(['message' => 'Aucun favori trouvé'], 404);
        }

        return response()->json($favorites);
    }


}
