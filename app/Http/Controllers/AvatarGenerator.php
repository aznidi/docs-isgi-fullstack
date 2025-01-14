<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Http;

class AvatarGenerator
{
    public static function createDefaultAvatar($email)
    {
        try {
            Log::info('Avatar generation started', ['email' => $email]);

            // Générer l'URL Gravatar
            $hash = md5(strtolower(trim($email)));
            $gravatarUrl = "https://www.gravatar.com/avatar/{$hash}?s=200&d=identicon";
            Log::info('Gravatar URL generated', ['url' => $gravatarUrl]);

            // Télécharger l'avatar depuis Gravatar
            $avatarContent = Http::get($gravatarUrl)->body();

            // Définir le chemin de stockage
            $avatarPath = 'public/avatars/' . uniqid() . '.png';

            // Stocker l'avatar dans le dossier public
            Storage::put($avatarPath, $avatarContent);
            Log::info('Avatar saved to storage', ['path' => $avatarPath]);

            // Retourner le chemin relatif pour la base de données
            return str_replace('public/', '', $avatarPath);
        } catch (\Exception $e) {
            Log::error('Error generating avatar', ['error' => $e->getMessage()]);
            return null;
        }
    }
}
