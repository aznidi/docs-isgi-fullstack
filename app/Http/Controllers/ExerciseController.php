<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class ExerciseController extends Controller
{
    // 1. Liste des exercices avec recherche et filtrage
    public function index(Request $request)
    {
        $query = Exercise::query();

        // Filtrage par module
        if ($request->has('module')) {
            $query->whereHas('module', function ($q) use ($request) {
                $q->where('nomMod', $request->input('module'));
            });
        }

        // Filtrage par niveau de difficulté
        if ($request->has('difficulty')) {
            $query->where('level', $request->input('difficulty'));
        }

        // Recherche par mot-clé dans le titre ou la description
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%')
                  ->orWhere('description', 'like', '%' . $request->input('search') . '%');
        }

        $exercises = $query->with('module')->paginate(10);

        return response()->json($exercises);
    }

    // 2. Détails d'un exercice spécifique
    public function show($id)
    {
        $exercise = Exercise::with('module', 'solutions')->findOrFail($id);

        return response()->json($exercise);
    }

    // 3. Création d'un nouvel exercice
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'module_id' => 'required|exists:modules,id',
            'level' => 'required|in:facile,intermédiaire,avancé',
            'instructions' => 'nullable|string',
            'path' => 'nullable|max:10240', // Maximum 10MB
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Sauvegarde des fichiers
        $filePath = null;
        $imagePath = null;

        if ($request->hasFile('path')) {
            $filePath = $request->file('path')->store('exercises/files', 'public');
        }

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('exercises/images', 'public');
        }

        $exercise = Exercise::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'module_id' => $request->input('module_id'),
            'level' => $request->input('level'),
            'instructions' => $request->input('instructions'),
            'path' => $filePath,
            'image_path' => $imagePath,
        ]);

        return response()->json($exercise, 201);
    }

    // 4. Mise à jour d'un exercice
    public function update(Request $request, $id)
    {
        $exercise = Exercise::findOrFail($id);

        // Log des données reçues
        Log::info('Champs reçus pour mise à jour :', $request->all());

        $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'module_id' => 'sometimes|exists:modules,id',
            'level' => 'sometimes|in:facile,intermédiaire,avancé',
            'instructions' => 'nullable|string',
            'path' => 'nullable|file|max:10240',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Mise à jour des fichiers
        if ($request->hasFile('path')) {
            if ($exercise->path) {
                Storage::disk('public')->delete($exercise->path);
            }
            $exercise->path = $request->file('path')->store('exercises/files', 'public');
        }

        if ($request->hasFile('image')) {
            if ($exercise->image_path) {
                Storage::disk('public')->delete($exercise->image_path);
            }
            $exercise->image_path = $request->file('image')->store('exercises/images', 'public');
        }

        // Mise à jour des champs non-fichiers
        $exercise->update($request->only(['title', 'description', 'module_id', 'level', 'instructions']));

        // Log des données après mise à jour
        Log::info('Exercice mis à jour :', $exercise->toArray());

        return response()->json($exercise);
    }


    // 5. Suppression d'un exercice
    public function destroy($id)
    {
        $exercise = Exercise::findOrFail($id);

        if ($exercise->path) {
            Storage::disk('public')->delete($exercise->path);
        }

        if ($exercise->image_path) {
            Storage::disk('public')->delete($exercise->image_path);
        }

        $exercise->delete();

        return response()->json(['message' => 'Exercise deleted successfully']);
    }
}
