<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    public function updateUsername(Request $request)
    {
        $request->validate([
            'username' => 'required|string|min:3|max:255',
        ]);

        $user = $request->user(); // Récupérer l'utilisateur authentifié
        $user->name = $request->username;
        $user->save();

        return response()->json(['message' => 'Nom d\'utilisateur mis à jour avec succès.']);
    }


    public function updateEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email', // Vérifie que l'email est unique
        ]);

        $user = $request->user(); // Récupérer l'utilisateur authentifié
        $user->email = $request->email;
        $user->email_verified_at = null; // Réinitialise la vérification d'email si nécessaire
        $user->save();

        return response()->json(['message' => 'Email mis à jour avec succès.']);
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required', // Vérifie que l'utilisateur fournit le mot de passe actuel
            'new_password' => 'required|min:8|confirmed', // Nouveau mot de passe confirmé
        ]);

        $user = $request->user();

        // Vérifier si le mot de passe actuel est correct
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(['message' => 'Le mot de passe actuel est incorrect.'], 422);
        }

        // Mettre à jour le mot de passe
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json(['message' => 'Mot de passe mis à jour avec succès.']);
    }


    public function destroy(Request $request)
    {
        $request->validate([
            'password' => ['required', 'current_password'], // Vérifie que le mot de passe est correct
        ]);

        $user = $request->user(); // Récupérer l'utilisateur authentifié

        // Déconnexion de l'utilisateur
        Auth::logout();

        // Suppression de l'utilisateur
        $user->delete();

        // Invalidation de la session
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json(['message' => 'Votre compte a été supprimé avec succès.']);
    }




    public function updateAvatar(Request $request)
    {
        // Vérification de la présence du fichier
        if (!$request->hasFile('profile_image')) {
            return response()->json(['message' => 'Aucun fichier reçu.'], 400);
        }

        // Validation de l'image
        $request->validate([
            'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = $request->user();

        // Supprimer l'ancien avatar
        if ($user->profile_image) {
            if (Storage::exists("public/{$user->profile_image}")) {
                Storage::delete("public/{$user->profile_image}");
            }
        }

        // Stockage du nouveau fichier
        if ($request->file('profile_image')->isValid()) {
            $path = $request->file('profile_image')->store('avatars', 'public');
        } else {
            return response()->json(['message' => 'Le fichier téléchargé n\'est pas valide.'], 400);
        }

        // Mise à jour de la base de données
        $user->profile_image = $path;
        $user->save();

        return response()->json([
            'message' => 'Image de profil mise à jour avec succès.',
            'profile_image' => $path,
        ]);
    }





}
