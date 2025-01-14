<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\AvatarGenerator;

class GoogleAuthController extends Controller
{
    /**
     * Redirige l'utilisateur vers Google pour l'authentification.
     */
    public function redirectToGoogle()
    {
        // Générer l'URL de redirection pour Google OAuth
        $url = Socialite::driver('google')
            ->stateless()
            ->redirect()
            ->getTargetUrl();

        // Retourner l'URL générée
        return response()->json(['redirect_url' => $url]);
    }

    /**
     * Gère le callback après l'authentification avec Google.
     */
    public function handleGoogleCallback()
    {
        try {
            // Vérifiez si le code est présent
            if (!request()->has('code')) {
                return response()->json(['error' => 'Le paramètre code est manquant'], 400);
            }

            // Récupérer les informations de l'utilisateur Google
            $googleUser = Socialite::driver('google')->stateless()->user();

            // Télécharger l'avatar de Google
            $avatarUrl = $googleUser->avatar;
            $avatarContent = Http::get($avatarUrl)->body();
            $avatarPath = 'avatars/' . uniqid() . '.jpg'; // Chemin relatif sans "public/"

            // Sauvegarder l'avatar dans le dossier public/storage
            Storage::put('public/' . $avatarPath, $avatarContent);

            // Créer ou mettre à jour l'utilisateur dans la base de données


            $user = User::updateOrCreate(
                ['email' => $googleUser->email],
                [
                    'name' => $googleUser->name,
                    'password' => bcrypt('random-password'),
                    'role' => 'user',
                    'profile_image' => $googleUser->avatar ?? AvatarGenerator::createDefaultAvatar($googleUser->email),
                ]
            );


            // Générer le token pour l'utilisateur
            $token = $user->createToken('auth_token')->plainTextToken;

            // Rediriger avec le token en paramètre d'URL
            return redirect()->away("http://localhost:3000/login/callback?token={$token}");
        } catch (\Exception $e) {
            Log::error('Erreur lors de la connexion avec Google :', ['error' => $e->getMessage()]);

            return response()->json([
                'error' => 'Erreur lors de la connexion avec Google',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
}
